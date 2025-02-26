<template>
  <div class="help-container" :class="{ 'help-visible': isHelpVisible }">
    <transition name="slide">
      <HelpGuide class="help" v-if="isHelpVisible" />
    </transition>
    <button class="help-button" @click="toggleHelp" :class="{ active: isHelpVisible }" aria-label="Toggle help guide">
      <span v-if="isHelpVisible">×</span>
      <span v-else>?</span>
    </button>
  </div>

  <div class="home-container">
    <h1>Guitar Chord Sequence Visualizer</h1>
    <ChordInput v-model="selectedChords" />

    <div class="chord-display-section">
      <div v-if="selectedChords.length > 0">
        <h2>Selected Chords <span class="chord-count">({{ selectedChords.length }} of {{ totalChords }})</span></h2>
        <ChordSequenceDisplay :parsed-chords="selectedChords" />
      </div>

      <div class="all-chords-section">
        <h2>All Available Chords <span class="chord-count">({{ allChords.length }} chords)</span></h2>
        <ChordSequenceDisplay :parsed-chords="allChords" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import ChordInput from '@/components/ChordInput.vue'
import ChordSequenceDisplay from '@/components/ChordSequenceDisplay.vue'
import HelpGuide from '@/components/HelpGuide.vue'
import { chordDictionary } from '../chordDictionary'

// State
const selectedChords = ref([]) // Empty by default
const isHelpVisible = ref(false)

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
</script>

<style>
.help-container {
  position: fixed;
  top: 20px;
  right: 20px;
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
  background-color: #2196f3;
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
  background-color: #1976d2;
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
  transform: translateX(30px);
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
</style>
