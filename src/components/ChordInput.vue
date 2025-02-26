<template>
  <div class="chord-input-container">
    <div class="selected-chords" @dragover.prevent @drop="onDrop">
      <TransitionGroup name="tag" tag="div" class="tag-container">
        <div v-for="(selectedChord, index) in selectedChords" :key="selectedChord + index" class="chord-tag"
          draggable="true" @dragstart="startDrag($event, index)" @dragover.prevent @dragenter.prevent>
          {{ selectedChord }}
          <span class="remove-chord" @click="removeChord(index)">×</span>
        </div>
      </TransitionGroup>
      <input type="text" placeholder="Search for a chord..." v-model="searchText" @input="searchChords"
        @keydown.backspace="handleBackspace" @keydown.enter="handleEnter" ref="searchInput" />
    </div>

    <transition name="fade">
      <div class="search-results" v-if="filteredChords.length && searchText">
        <div v-for="chord in filteredChords" :key="chord" class="chord-result" @click="addChord(chord)">
          {{ chord }}
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { chordDictionary } from '../chordDictionary'

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
const selectedChords = ref([])
const searchInput = ref(null)
const draggedItem = ref(null)

// Set initial selected chords from props
onMounted(() => {
  if (props.modelValue && props.modelValue.length) {
    selectedChords.value = [...props.modelValue]
  }
})

// Watch for changes to selected chords and emit update
watch(
  selectedChords,
  (newValue) => {
    emit('update:modelValue', newValue)
  },
  { deep: true },
)

// Available chords from the dictionary
const availableChords = computed(() => {
  return Object.keys(chordDictionary)
})

// Filtered chords based on search text
const filteredChords = computed(() => {
  if (!searchText.value) return []

  const filtered = availableChords.value.filter(
    (chord) =>
      chord.toLowerCase().includes(searchText.value.toLowerCase()) &&
      !selectedChords.value.includes(chord),
  )

  // Limit the number of results to prevent overflow
  return filtered.slice(0, 20)
})

// Methods
function searchChords() {
  // The filtered chords are automatically updated via the computed property
}

function addChord(chord) {
  if (!selectedChords.value.includes(chord)) {
    selectedChords.value.push(chord)
    searchText.value = ''
    searchInput.value.focus()
  }
}

function removeChord(index) {
  selectedChords.value.splice(index, 1)
}

function handleBackspace(event) {
  // If search is empty and there are selected chords, remove the last chord
  if (searchText.value === '' && selectedChords.value.length > 0) {
    selectedChords.value.pop()
    event.preventDefault()
  }
}

function handleEnter(event) {
  // If there's at least one search result, add the first one
  if (filteredChords.value.length > 0) {
    addChord(filteredChords.value[0])
    event.preventDefault()
  }
}

// Drag and drop functions
function startDrag(event, index) {
  draggedItem.value = index
  // Add a visual cue that the element is being dragged
  event.dataTransfer.effectAllowed = 'move'
  // Required for Firefox
  event.dataTransfer.setData('text/plain', index)
}

function onDrop(event) {
  // Get the drop position
  const dropAreaElements = document.querySelectorAll('.chord-tag')
  const dropPosition = findDropPosition(event, dropAreaElements)

  // Reorder the chords
  if (draggedItem.value !== null && dropPosition !== null) {
    const item = selectedChords.value[draggedItem.value]
    // Remove from original position
    selectedChords.value.splice(draggedItem.value, 1)
    // Add at new position
    selectedChords.value.splice(dropPosition, 0, item)
  }

  draggedItem.value = null
}

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
</script>

<style>
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
  /* Light blue background */
  color: #1976d2;
  /* Blue text */
  padding: 4px 8px;
  border-radius: 16px;
  font-size: 14px;
  font-weight: 500;
  cursor: grab;
  user-select: none;
  transition: all 0.3s ease;
  will-change: transform, opacity;
}

.chord-tag:active {
  cursor: grabbing;
}

.chord-tag:hover {
  background-color: #bbdefb;
}

.remove-chord {
  margin-left: 6px;
  font-size: 16px;
  cursor: pointer;
  opacity: 0.7;
  transition: opacity 0.2s ease;
}

.remove-chord:hover {
  opacity: 1;
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
  max-height: 300px;
  overflow-y: auto;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  z-index: 100;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  gap: 5px;
  padding: 10px;
}

.chord-result {
  padding: 8px 12px;
  cursor: pointer;
  transition: background-color 0.2s ease;
  border-radius: 4px;
  text-align: center;
}

.chord-result:hover {
  background-color: #f5f5f5;
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
