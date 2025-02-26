<template>
  <div class="finger-legend">
    <div class="legend-title">Finger Guide</div>
    <div class="legend-items">
      <div class="legend-item">
        <div class="legend-color finger-1">1</div>
        <div class="legend-label">Index</div>
      </div>
      <div class="legend-item">
        <div class="legend-color finger-2">2</div>
        <div class="legend-label">Middle</div>
      </div>
      <div class="legend-item">
        <div class="legend-color finger-3">3</div>
        <div class="legend-label">Ring</div>
      </div>
      <div class="legend-item">
        <div class="legend-color finger-4">4</div>
        <div class="legend-label">Pinky</div>
      </div>
    </div>
  </div>
</template>

<script setup>
// No props or logic needed for this component
</script>

<style>
.finger-legend {
  margin-top: 20px;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 12px;
  background-color: #f9f9f9;
}

.legend-title {
  font-weight: bold;
  margin-bottom: 8px;
  font-size: 14px;
  color: #333;
  text-align: center;
}

.legend-items {
  display: flex;
  justify-content: space-around;
  align-items: center;
}

.legend-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
}

.legend-color {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.legend-label {
  font-size: 12px;
  color: #666;
}

.finger-1 {
  background-color: #9c27b0 !important; /* Purple for index finger */
}

.finger-2 {
  background-color: #ff9800 !important; /* Orange for middle finger */
}

.finger-3 {
  background-color: #4caf50 !important; /* Green for ring finger */
}

.finger-4 {
  background-color: #ff4081 !important; /* Bright pink for pinky finger */
}
</style>
