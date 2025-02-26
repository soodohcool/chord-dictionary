<template>
  <div class="help-guide">
    <div class="help-section">
      <h3>How to Use This App</h3>

      <div class="step">
        <div class="step-number">1</div>
        <div class="step-content">
          <strong>Search for chords</strong> you want to play by typing their names (like G, Am, C)
        </div>
      </div>

      <div class="step">
        <div class="step-number">2</div>
        <div class="step-content">
          <strong>Click on a chord</strong> from the search results to add it to your sequence
        </div>
      </div>

      <div class="step">
        <div class="step-number">3</div>
        <div class="step-content">
          <strong>Drag chords</strong> to change their order in your song
        </div>
      </div>

      <div class="step">
        <div class="step-number">4</div>
        <div class="step-content"><strong>Remove a chord</strong> by clicking the × on its tag</div>
      </div>
    </div>

    <div class="help-section">
      <h3>Understanding Chord Diagrams</h3>

      <div class="diagram-guide">
        <div class="diagram-item">
          <div class="diagram-symbol">○</div>
          <div class="diagram-explanation">Play the open string</div>
        </div>

        <div class="diagram-item">
          <div class="diagram-symbol">✕</div>
          <div class="diagram-explanation">Don't play this string</div>
        </div>

        <div class="diagram-item">
          <div class="diagram-symbol colored-dot">1</div>
          <div class="diagram-explanation">Place your finger here</div>
        </div>
      </div>

      <div class="tip">
        <strong>Tip:</strong> The colors show which finger to use. Look at the Finger Guide below to
        learn which color means which finger!
      </div>
    </div>

    <FingerLegend />
  </div>
</template>

<script setup>
import FingerLegend from './FingerLegend.vue'
</script>

<style>
.help-guide {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
  padding: 15px;
  max-width: 350px;
  font-size: 14px;
}

.help-section {
  margin-bottom: 20px;
  border-bottom: 1px solid #eee;
  padding-bottom: 15px;
}

.help-section:last-child {
  border-bottom: none;
  padding-bottom: 0;
  margin-bottom: 15px;
}

h3 {
  color: #2196f3;
  margin-top: 0;
  margin-bottom: 15px;
  font-size: 16px;
}

.step {
  display: flex;
  align-items: flex-start;
  margin-bottom: 10px;
}

.step-number {
  background-color: #2196f3;
  color: white;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  margin-right: 10px;
  flex-shrink: 0;
}

.step-content {
  flex: 1;
  line-height: 1.4;
}

.diagram-guide {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 15px;
}

.diagram-item {
  display: flex;
  align-items: center;
}

.diagram-symbol {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 10px;
  font-size: 16px;
}

.colored-dot {
  background-color: #4caf50;
  color: white;
  border-radius: 50%;
  font-weight: bold;
  font-size: 14px;
}

.diagram-explanation {
  flex: 1;
}

.tip {
  background-color: #e3f2fd;
  padding: 10px;
  border-radius: 4px;
  border-left: 4px solid #2196f3;
  margin-top: 15px;
  font-size: 13px;
  line-height: 1.4;
}
</style>
