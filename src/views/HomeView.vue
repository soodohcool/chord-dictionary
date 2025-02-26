<template>
  <div class="help-container" :class="{ 'help-visible': isHelpVisible }">
    <transition name="slide">
      <HelpGuide class="help" v-if="isHelpVisible" />
    </transition>
    <button
      class="help-button"
      @click="toggleHelp"
      :class="{ active: isHelpVisible }"
      aria-label="Toggle help guide"
    >
      <span v-if="isHelpVisible">×</span>
      <span v-else>?</span>
    </button>
  </div>

  <div class="home-container">
    <h1>Guitar Chord Sequence Visualizer</h1>
    <ChordInput v-model="selectedChords" />

    <div class="chord-display-section">
      <ChordSequenceDisplay :parsed-chords="selectedChords" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ChordInput from '@/components/ChordInput.vue'
import ChordSequenceDisplay from '@/components/ChordSequenceDisplay.vue'
import HelpGuide from '@/components/HelpGuide.vue'

// State
const selectedChords = ref(['G', 'D', 'Am', 'C']) // Default chord progression
const isHelpVisible = ref(false)

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

.chord-display-section {
  margin-top: 20px;
}
</style>
