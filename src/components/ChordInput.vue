<template>
  <div class="chord-input-container" ref="containerRef">
    <div class="selected-chords" @dragover.prevent @drop="onDrop">
      <TransitionGroup name="tag" tag="div" class="tag-container">
        <div v-for="(chord, index) in modelValue" :key="chord + index" class="chord-tag" draggable="true"
          @dragstart="startDrag($event, index)" @dragover.prevent @dragenter.prevent>
          {{ chord }}
          <span class="remove-chord" @click="removeChord(index)">×</span>
        </div>
      </TransitionGroup>
      <input type="text" placeholder="Search for a chord..." v-model="searchText" @input="searchChords"
        @keydown.backspace="handleBackspace" @keydown.enter="handleEnter" @focus="showResults = true" @blur="handleBlur"
        ref="searchInput" />
    </div>

    <transition name="fade">
      <div class="search-results" v-if="(filteredChords.length && searchText) || showResults">
        <div class="chord-categories">
          <div v-for="(chords, category) in groupedFilteredChords" :key="category" class="chord-category">
            <div class="category-title">{{ formatCategoryName(category) }}</div>
            <div class="category-chords">
              <div v-for="chord in chords" :key="chord" class="chord-result" @click="selectChord(chord)"
                :class="{ 'selected': modelValue.includes(chord) }">
                {{ chord }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onBeforeUnmount } from 'vue'
import { chordDictionary } from '../chordDictionary'

/**
 * Chord input component for searching and selecting chords
 * 
 * @prop {Array} modelValue - Array of selected chord names
 * @emits update:modelValue - Emitted when selected chords change
 */

// Props and emits
const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['update:modelValue'])

// State
const searchText = ref('')
const searchInput = ref(null)
const draggedItem = ref(null)
const showResults = ref(false)
const containerRef = ref(null)

// Available chords from the dictionary
const availableChords = computed(() => {
  return Object.keys(chordDictionary)
})

// Filtered chords based on search text
const filteredChords = computed(() => {
  if (!searchText.value && !showResults.value) return []

  let filtered = availableChords.value

  if (searchText.value) {
    filtered = filtered.filter(
      (chord) => chord.toLowerCase().includes(searchText.value.toLowerCase())
    )
  }

  return filtered
})

/**
 * Group chords by category (Major, Minor, 7th, etc.)
 */
const groupedFilteredChords = computed(() => {
  const grouped = {}

  filteredChords.value.forEach(chord => {
    let category = 'other'

    if (chord.endsWith('m') && !chord.includes('maj') && !chord.includes('dim') && !chord.includes('aug')) {
      category = 'minor'
    } else if (chord.includes('7') && !chord.includes('maj')) {
      category = 'seventh'
    } else if (chord.includes('maj7')) {
      category = 'major7'
    } else if (chord.includes('dim')) {
      category = 'diminished'
    } else if (chord.includes('aug')) {
      category = 'augmented'
    } else if (chord.includes('sus')) {
      category = 'suspended'
    } else if (chord.match(/^[A-G]$/)) {
      category = 'major'
    }

    if (!grouped[category]) {
      grouped[category] = []
    }

    grouped[category].push(chord)
  })

  // Sort chords within each category
  Object.keys(grouped).forEach(category => {
    grouped[category].sort()
  })

  // Sort categories by priority
  const sortedGrouped = {}
  const categoryOrder = ['major', 'minor', 'seventh', 'major7', 'diminished', 'augmented', 'suspended', 'other']

  categoryOrder.forEach(category => {
    if (grouped[category] && grouped[category].length > 0) {
      sortedGrouped[category] = grouped[category]
    }
  })

  return sortedGrouped
})

/**
 * Format category name for display
 * 
 * @param {string} category - Category name
 * @returns {string} - Formatted category name
 */
function formatCategoryName(category) {
  const nameMap = {
    'major': 'Major',
    'minor': 'Minor',
    'seventh': '7th',
    'major7': 'Major 7th',
    'diminished': 'Diminished',
    'augmented': 'Augmented',
    'suspended': 'Suspended',
    'other': 'Other'
  }

  return nameMap[category] || category
}

/**
 * Search for chords based on input text
 */
function searchChords() {
  // The filtered chords are automatically updated via the computed property
  showResults.value = true
}

/**
 * Select a chord from the dropdown
 * 
 * @param {string} chord - Chord name to select
 */
function selectChord(chord) {
  if (!props.modelValue.includes(chord)) {
    const newChords = [...props.modelValue, chord]
    emit('update:modelValue', newChords)
    searchText.value = ''
    showResults.value = false

    // Focus the input after the DOM has updated
    nextTick(() => {
      if (searchInput.value) {
        searchInput.value.focus()
      }
    })
  }
}

/**
 * Remove a chord from the selected chords
 * 
 * @param {number} index - Index of chord to remove
 */
function removeChord(index) {
  const newChords = [...props.modelValue]
  newChords.splice(index, 1)
  emit('update:modelValue', newChords)
}

/**
 * Handle backspace key press
 * 
 * @param {Event} event - Keyboard event
 */
function handleBackspace(event) {
  // If search is empty and there are selected chords, remove the last chord
  if (searchText.value === '' && props.modelValue.length > 0) {
    const newChords = [...props.modelValue]
    newChords.pop()
    emit('update:modelValue', newChords)
    event.preventDefault()
  }
}

/**
 * Handle enter key press
 * 
 * @param {Event} event - Keyboard event
 */
function handleEnter(event) {
  // If there's at least one search result, add the first one
  const firstCategory = Object.keys(groupedFilteredChords.value)[0]
  if (firstCategory && groupedFilteredChords.value[firstCategory].length > 0) {
    selectChord(groupedFilteredChords.value[firstCategory][0])
    event.preventDefault()
  }
}

/**
 * Handle input blur
 */
function handleBlur() {
  // Delay hiding results to allow for clicking on a result
  setTimeout(() => {
    showResults.value = false
  }, 200)
}

/**
 * Handle click outside the component
 * 
 * @param {Event} event - Click event
 */
function handleClickOutside(event) {
  if (containerRef.value && !containerRef.value.contains(event.target)) {
    showResults.value = false
  }
}

/**
 * Start drag operation
 * 
 * @param {DragEvent} event - Drag event
 * @param {number} index - Index of chord being dragged
 */
function startDrag(event, index) {
  draggedItem.value = index
  // Add a visual cue that the element is being dragged
  event.dataTransfer.effectAllowed = 'move'
  // Required for Firefox
  event.dataTransfer.setData('text/plain', index)
}

/**
 * Handle drop event for reordering chords
 * 
 * @param {DragEvent} event - Drop event
 */
function onDrop(event) {
  // Get the drop position
  const dropAreaElements = document.querySelectorAll('.chord-tag')
  const dropPosition = findDropPosition(event, dropAreaElements)

  // Reorder the chords
  if (draggedItem.value !== null && dropPosition !== null) {
    const newChords = [...props.modelValue]
    const item = newChords[draggedItem.value]
    // Remove from original position
    newChords.splice(draggedItem.value, 1)
    // Add at new position
    newChords.splice(dropPosition, 0, item)
    emit('update:modelValue', newChords)
  }

  draggedItem.value = null
}

/**
 * Find the position to drop a chord
 * 
 * @param {DragEvent} event - Drop event
 * @param {NodeList} elements - List of chord tag elements
 * @returns {number} - Index to drop at
 */
function findDropPosition(event, elements) {
  // Find the closest element to the drop position
  for (let i = 0; i < elements.length; i++) {
    const rect = elements[i].getBoundingClientRect()
    const mouseX = event.clientX

    // If we're before the middle of an element, insert before it
    if (mouseX < rect.left + rect.width / 2) {
      return i
    }
  }

  // If we're after all elements, append to the end
  return elements.length
}

// Add event listener for clicks outside the component
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

// Remove event listener when component is unmounted
onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.chord-input-container {
  width: 100%;
  margin-bottom: 20px;
  position: relative;
}

.selected-chords {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: white;
  min-height: 40px;
  margin-bottom: 10px;
}

.tag-container {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.chord-tag {
  display: flex;
  align-items: center;
  background-color: #e3f2fd;
  color: #1976d2;
  padding: 4px 8px;
  border-radius: 16px;
  font-size: 14px;
  font-weight: 500;
  cursor: grab;
  user-select: none;
  transition: all 0.3s ease;
  will-change: transform, opacity;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.chord-tag:active {
  cursor: grabbing;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.chord-tag:hover {
  background-color: #bbdefb;
  transform: translateY(-1px);
}

.remove-chord {
  margin-left: 6px;
  font-size: 16px;
  cursor: pointer;
  opacity: 0.7;
  transition: opacity 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
  border-radius: 50%;
}

.remove-chord:hover {
  opacity: 1;
  background-color: rgba(0, 0, 0, 0.1);
}

input {
  flex: 1;
  min-width: 120px;
  border: none;
  outline: none;
  font-size: 16px;
  padding: 4px;
}

.search-results {
  position: absolute;
  width: 100%;
  max-height: 400px;
  overflow-y: auto;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  z-index: 100;
  padding: 10px;
}

.chord-categories {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.chord-category {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.category-title {
  font-weight: 600;
  color: #555;
  font-size: 14px;
  padding: 0 5px;
  border-bottom: 1px solid #eee;
}

.category-chords {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
  gap: 5px;
}

.chord-result {
  padding: 8px 12px;
  cursor: pointer;
  transition: all 0.2s ease;
  border-radius: 4px;
  text-align: center;
  background-color: #f5f5f5;
  font-size: 14px;
}

.chord-result:hover {
  background-color: #e3f2fd;
  transform: translateY(-1px);
}

.chord-result.selected {
  opacity: 0.5;
  pointer-events: none;
}

/* Fade transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Tag transition */
.tag-enter-active,
.tag-leave-active {
  transition: all 0.3s ease;
}

.tag-enter-from,
.tag-leave-to {
  opacity: 0;
  transform: translateX(-10px);
}
</style>
