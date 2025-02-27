<template>
  <div class="buttons-container">
    <MainNavigation :active-section="activeSection" @navigate="handleNavigation" />

    <div class="help-container" :class="{ 'help-visible': isHelpVisible }">
      <transition name="slide">
        <HelpGuide class="help" v-if="isHelpVisible" />
      </transition>
      <button class="help-button" @click="toggleHelp" :class="{ active: isHelpVisible }" aria-label="Toggle help guide">
        <span v-if="isHelpVisible">×</span>
        <span v-else>?</span>
      </button>
    </div>
  </div>

  <div class="home-container">
    <h1>Guitar Chord Sequence Visualizer</h1>

    <div v-if="activeSection !== 'about'">
      <ChordInput v-model="selectedChords" />

      <div class="chord-actions" v-if="selectedChords.length > 0">
        <button v-if="isLoggedIn" @click="showSaveForm = true" class="save-button">
          Save Progression
        </button>
        <span v-else class="login-prompt">
          <a href="#" @click.prevent="$emit('login-required')">Login</a> to save your chord progressions
        </span>
      </div>

      <div class="chord-display-section">
        <div v-if="selectedChords.length > 0 && (activeSection === 'home' || activeSection === 'selected')">
          <h2 id="selected-chords-section">Selected Chords <span class="chord-count">({{ selectedChords.length }} of {{
            totalChords }})</span></h2>
          <ChordSequenceDisplay :parsed-chords="selectedChords" />
        </div>

        <div class="all-chords-section" v-if="activeSection === 'home' || activeSection === 'all'">
          <h2 id="all-chords-section">All Available Chords <span class="chord-count">({{ allChords.length }}
              chords)</span></h2>
          <ChordSequenceDisplay :parsed-chords="allChords" />
        </div>
      </div>
    </div>

    <AboutSection v-if="activeSection === 'about'" />
  </div>

  <!-- Save Progression Modal -->
  <div v-if="showSaveForm && isLoggedIn" class="modal-overlay" @click.self="showSaveForm = false">
    <div class="modal-content">
      <h3>Save Chord Progression</h3>

      <div class="form-group">
        <label for="progression-name">Name</label>
        <input id="progression-name" v-model="progressionName" type="text" required
          placeholder="Enter a name for this progression" />
      </div>

      <div class="form-group">
        <label>Chords</label>
        <div class="chords-preview">
          <span v-for="(chord, index) in selectedChords" :key="index" class="chord-tag">
            {{ chord }}
          </span>
        </div>
      </div>

      <div class="form-group checkbox">
        <input id="is-public" v-model="isPublic" type="checkbox" />
        <label for="is-public">Make this progression public</label>
      </div>

      <div class="form-actions">
        <button class="cancel-button" @click="showSaveForm = false">Cancel</button>
        <button class="save-button" @click="saveProgression" :disabled="isSaving || !progressionName">
          {{ isSaving ? 'Saving...' : 'Save' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, inject } from 'vue'
import ChordInput from '@/components/ChordInput.vue'
import ChordSequenceDisplay from '@/components/ChordSequenceDisplay.vue'
import HelpGuide from '@/components/HelpGuide.vue'
import MainNavigation from '@/components/MainNavigation.vue'
import AboutSection from '@/components/AboutSection.vue'
import { chordDictionary } from '../chordDictionary'

// Props
const props = defineProps({
  isLoggedIn: {
    type: Boolean,
    default: false
  },
  currentUser: {
    type: Object,
    default: null
  },
  currentChords: {
    type: Array,
    default: () => []
  }
})

// Injected values
const isDevelopmentMode = inject('isDevelopmentMode', ref(false))
const apiUrl = inject('apiUrl', '/backend')

// Emits
const emit = defineEmits(['update:current-chords', 'login-required'])

// State
const selectedChords = ref([]) // Empty by default
const isHelpVisible = ref(false)
const activeSection = ref('home')
const showSaveForm = ref(false)
const progressionName = ref('')
const isPublic = ref(false)
const isSaving = ref(false)

// Sync with parent component's currentChords
watch(() => props.currentChords, (newChords) => {
  if (newChords && newChords.length && JSON.stringify(newChords) !== JSON.stringify(selectedChords.value)) {
    selectedChords.value = [...newChords]
  }
}, { immediate: true })

// Update parent when selectedChords changes
watch(selectedChords, (newChords) => {
  if (JSON.stringify(newChords) !== JSON.stringify(props.currentChords)) {
    emit('update:current-chords', [...newChords])
  }
}, { deep: true })

// Computed property to get all available chords
const allChords = computed(() => {
  return Object.keys(chordDictionary)
})

// Computed property for total number of chords in the dictionary
const totalChords = computed(() => {
  return allChords.value.length
})

// Methods
const toggleHelp = () => {
  isHelpVisible.value = !isHelpVisible.value
}

const handleNavigation = (section) => {
  activeSection.value = section

  // Scroll to the appropriate section after a short delay to allow for rendering
  if (section === 'selected' || section === 'all') {
    setTimeout(() => {
      const elementId = section === 'selected' ? 'selected-chords-section' : 'all-chords-section'
      const element = document.getElementById(elementId)
      if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' })
      }
    }, 100)
  }
}

/**
 * Save the current progression
 */
const saveProgression = async () => {
  if (!props.isLoggedIn || !progressionName.value) return

  isSaving.value = true

  try {
    // Check if we're in development mode without a backend
    if (isDevelopmentMode.value && !window.location.hostname.includes('localhost')) {
      // Simulate successful save in development
      setTimeout(() => {
        progressionName.value = ''
        isPublic.value = false
        showSaveForm.value = false
        alert('Progression saved successfully! (Development mode)')
        isSaving.value = false
      }, 500)
      return
    }

    const apiEndpoint = `${apiUrl}/api/progressions.php`

    const response = await fetch(apiEndpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        name: progressionName.value,
        chords: selectedChords.value,
        is_public: isPublic.value
      }),
      credentials: 'include' // Include cookies in the request
    })

    // Check if response is JSON
    const contentType = response.headers.get('content-type')
    if (!contentType || !contentType.includes('application/json')) {
      console.warn('API returned non-JSON response')
      alert('Error saving progression. Server returned an invalid response.')
      return
    }

    const data = await response.json()

    if (data.status === 'success') {
      // Reset form
      progressionName.value = ''
      isPublic.value = false
      showSaveForm.value = false

      // Show success message (you could implement a toast notification here)
      alert('Progression saved successfully!')
    } else {
      console.error('Failed to save progression:', data.message)
      alert('Failed to save progression: ' + (data.message || 'Unknown error'))
    }
  } catch (err) {
    console.error('Error saving progression:', err)
    alert('Error saving progression. Please try again.')
  } finally {
    isSaving.value = false
  }
}

// Watch for changes in the URL hash to update the active section
onMounted(() => {
  const hash = window.location.hash.substring(1)
  if (hash) {
    const validSections = ['home', 'selected', 'all', 'about']
    if (validSections.includes(hash)) {
      activeSection.value = hash
    }
  }

  // Listen for hash changes
  window.addEventListener('hashchange', () => {
    const hash = window.location.hash.substring(1)
    if (hash) {
      const validSections = ['home', 'selected', 'all', 'about']
      if (validSections.includes(hash)) {
        activeSection.value = hash
      }
    }
  })
})

// Update the URL hash when the active section changes
watch(activeSection, (newSection) => {
  window.location.hash = newSection
})
</script>

<style>
.buttons-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.help-container {
  position: relative;
  z-index: 1000;
}

.help {
  position: absolute;
  top: 0;
  right: 50px;
  width: 350px;
  max-height: 80vh;
  overflow-y: auto;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
}

.help-button {
  position: relative;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #757575;
  color: white;
  font-size: 20px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition:
    background-color 0.3s ease,
    transform 0.3s ease;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  z-index: 1001;
}

.help-button:hover {
  background-color: #616161;
}

.help-button.active {
  background-color: #f44336;
  transform: rotate(90deg);
}

/* Slide transition */
.slide-enter-active,
.slide-leave-active {
  transition:
    transform 0.3s ease,
    opacity 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateX(-30px);
  opacity: 0;
}

.home-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  text-align: center;
  color: #333;
  margin-bottom: 20px;
}

h2 {
  margin-top: 30px;
  margin-bottom: 15px;
  color: #555;
  font-size: 1.5rem;
  scroll-margin-top: 80px;
  /* Add space for scrolling with fixed header */
}

.chord-count {
  font-size: 0.9rem;
  color: #777;
  font-weight: normal;
}

.chord-display-section {
  margin-top: 20px;
}

.all-chords-section {
  margin-top: 40px;
}

.chord-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 10px;
}

.save-button {
  padding: 8px 16px;
  background-color: #2196f3;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.save-button:hover {
  background-color: #1976d2;
}

.save-button:disabled {
  background-color: #b0bec5;
  cursor: not-allowed;
}

.login-prompt {
  font-size: 14px;
  color: #666;
}

.login-prompt a {
  color: #2196f3;
  text-decoration: none;
  font-weight: 500;
}

.login-prompt a:hover {
  text-decoration: underline;
}

/* Modal styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  width: 100%;
  max-width: 500px;
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-content h3 {
  margin-top: 0;
  margin-bottom: 20px;
  color: #333;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
  color: #555;
}

.form-group input[type="text"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.form-group.checkbox {
  display: flex;
  align-items: center;
}

.form-group.checkbox input {
  margin-right: 8px;
}

.form-group.checkbox label {
  margin-bottom: 0;
  font-weight: normal;
}

.chords-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  padding: 10px;
  background-color: #f5f5f5;
  border-radius: 4px;
  min-height: 40px;
}

.chord-tag {
  display: inline-flex;
  align-items: center;
  padding: 4px 8px;
  background-color: #e3f2fd;
  color: #1976d2;
  border-radius: 16px;
  font-size: 12px;
  font-weight: 500;
  margin: 2px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}

.cancel-button {
  padding: 8px 16px;
  background-color: #f5f5f5;
  color: #555;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
}
</style>
