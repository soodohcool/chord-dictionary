<template>
    <div class="about-section">
        <h2>About Guitar Chord Sequence Visualizer</h2>
        <p>
            This application helps guitarists visualize chord sequences and explore different chord options.
            It provides a comprehensive library of guitar chords with accurate finger positions.
        </p>
        <h3>Features:</h3>
        <ul>
            <li>Search and select from {{ totalChords }} different guitar chords</li>
            <li>Visualize chord fingerings with accurate fretboard diagrams</li>
            <li>Create and save custom chord progressions</li>
            <li>Drag and drop to reorder your chord sequence</li>
        </ul>
        <h3>How to Use:</h3>
        <ol>
            <li>Use the search box to find chords by name</li>
            <li>Click on a chord to add it to your sequence</li>
            <li>Drag chords to reorder them in your progression</li>
            <li>Click the × to remove a chord from your sequence</li>
        </ol>
        <p class="version">Version 1.0.0</p>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { chordDictionary } from '../chordDictionary'

// Computed property for total number of chords in the dictionary
const totalChords = computed(() => {
    return Object.keys(chordDictionary).length
})
</script>

<style scoped>
.about-section {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    margin-bottom: 20px;
}

h3 {
    color: #555;
    margin-top: 25px;
    margin-bottom: 10px;
}

p {
    line-height: 1.6;
    color: #666;
}

ul,
ol {
    padding-left: 20px;
    margin-bottom: 20px;
}

li {
    margin-bottom: 8px;
    line-height: 1.5;
    color: #666;
}

.version {
    margin-top: 30px;
    font-size: 0.9rem;
    color: #999;
    text-align: right;
}
</style>