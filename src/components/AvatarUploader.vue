<template>
    <div class="avatar-uploader">
        <div class="modal-overlay" @click.self="$emit('close')">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Update Profile Picture</h3>
                    <button class="btn-icon close-button" @click="$emit('close')">×</button>
                </div>

                <div class="modal-body">
                    <div v-if="!selectedFile" class="upload-area">
                        <div class="upload-prompt">
                            <div class="upload-icon">📷</div>
                            <p>Drag and drop an image here, or click to select</p>
                            <p class="upload-hint">Recommended: Square image, at least 200x200 pixels</p>
                        </div>
                        <input type="file" ref="fileInput" @change="handleFileSelect" accept="image/*"
                            class="file-input" />
                    </div>

                    <div v-else class="preview-area">
                        <div class="preview-container" ref="previewContainer">
                            <img :src="previewUrl" ref="previewImage" class="preview-image" @load="initializeCropper" />
                        </div>
                        <div class="preview-controls">
                            <button class="btn btn-light" @click="resetSelection">
                                <span class="icon">↩️</span> Choose Different Image
                            </button>
                            <div class="zoom-controls">
                                <button class="btn btn-icon btn-light zoom-button" @click="zoomOut"
                                    :disabled="zoomLevel <= 1">
                                    <span class="icon">➖</span>
                                </button>
                                <div class="zoom-slider">
                                    <input type="range" min="1" max="3" step="0.1" v-model="zoomLevel"
                                        @input="updateZoom" />
                                </div>
                                <button class="btn btn-icon btn-light zoom-button" @click="zoomIn"
                                    :disabled="zoomLevel >= 3">
                                    <span class="icon">➕</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" @click="$emit('close')">Cancel</button>
                    <button class="btn btn-primary" @click="saveAvatar" :disabled="!selectedFile || isSaving">
                        {{ isSaving ? 'Saving...' : 'Save Profile Picture' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, inject } from 'vue'

/**
 * Avatar uploader component that allows users to upload and crop their profile picture
 * 
 * @emits update - Emitted when user saves a new avatar with the avatar URL
 * @emits close - Emitted when user closes the uploader
 */

const emit = defineEmits(['update', 'close'])

// Injected values
const isDevelopmentMode = inject('isDevelopmentMode', ref(false))
const apiUrl = inject('apiUrl', '/backend')

// Refs
const fileInput = ref(null)
const previewImage = ref(null)
const previewContainer = ref(null)

// State
const selectedFile = ref(null)
const previewUrl = ref('')
const zoomLevel = ref(1)
const isSaving = ref(false)
const cropperPosition = ref({ x: 0, y: 0 })
const isDragging = ref(false)
const dragStart = ref({ x: 0, y: 0 })

/**
 * Handle file selection from input
 * 
 * @param {Event} event - File input change event
 */
const handleFileSelect = (event) => {
    const file = event.target.files[0]
    if (file && file.type.startsWith('image/')) {
        selectedFile.value = file
        previewUrl.value = URL.createObjectURL(file)
        zoomLevel.value = 1
    }
}

/**
 * Reset the selection and allow choosing a different image
 */
const resetSelection = () => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
    }
    selectedFile.value = null
    previewUrl.value = ''
    zoomLevel.value = 1

    // Clear the file input
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

/**
 * Initialize the cropper functionality after image loads
 */
const initializeCropper = () => {
    if (!previewImage.value || !previewContainer.value) return

    // Center the image initially
    centerImage()

    // Add event listeners for dragging
    previewImage.value.addEventListener('mousedown', startDrag)
    previewImage.value.addEventListener('touchstart', startDrag, { passive: false })
}

/**
 * Center the image in the preview container
 */
const centerImage = () => {
    if (!previewImage.value || !previewContainer.value) return

    const containerWidth = previewContainer.value.offsetWidth
    const containerHeight = previewContainer.value.offsetHeight
    const imageWidth = previewImage.value.offsetWidth * zoomLevel.value
    const imageHeight = previewImage.value.offsetHeight * zoomLevel.value

    cropperPosition.value = {
        x: (containerWidth - imageWidth) / 2,
        y: (containerHeight - imageHeight) / 2
    }

    updateImagePosition()
}

/**
 * Start dragging the image
 * 
 * @param {Event} event - Mouse or touch event
 */
const startDrag = (event) => {
    event.preventDefault()

    isDragging.value = true

    // Get the starting position
    if (event.type === 'touchstart') {
        dragStart.value = {
            x: event.touches[0].clientX - cropperPosition.value.x,
            y: event.touches[0].clientY - cropperPosition.value.y
        }
    } else {
        dragStart.value = {
            x: event.clientX - cropperPosition.value.x,
            y: event.clientY - cropperPosition.value.y
        }
    }

    // Add move and end event listeners
    document.addEventListener('mousemove', dragImage)
    document.addEventListener('touchmove', dragImage, { passive: false })
    document.addEventListener('mouseup', endDrag)
    document.addEventListener('touchend', endDrag)
}

/**
 * Drag the image
 * 
 * @param {Event} event - Mouse or touch event
 */
const dragImage = (event) => {
    if (!isDragging.value) return
    event.preventDefault()

    let clientX, clientY

    if (event.type === 'touchmove') {
        clientX = event.touches[0].clientX
        clientY = event.touches[0].clientY
    } else {
        clientX = event.clientX
        clientY = event.clientY
    }

    cropperPosition.value = {
        x: clientX - dragStart.value.x,
        y: clientY - dragStart.value.y
    }

    updateImagePosition()
}

/**
 * End dragging the image
 */
const endDrag = () => {
    isDragging.value = false

    // Remove event listeners
    document.removeEventListener('mousemove', dragImage)
    document.removeEventListener('touchmove', dragImage)
    document.removeEventListener('mouseup', endDrag)
    document.removeEventListener('touchend', endDrag)
}

/**
 * Update the image position based on current state
 */
const updateImagePosition = () => {
    if (!previewImage.value) return

    previewImage.value.style.transform = `translate(${cropperPosition.value.x}px, ${cropperPosition.value.y}px) scale(${zoomLevel.value})`
}

/**
 * Zoom in the image
 */
const zoomIn = () => {
    zoomLevel.value = Math.min(zoomLevel.value + 0.1, 3)
    updateZoom()
}

/**
 * Zoom out the image
 */
const zoomOut = () => {
    zoomLevel.value = Math.max(zoomLevel.value - 0.1, 1)
    updateZoom()
}

/**
 * Update zoom level and recenter image
 */
const updateZoom = () => {
    centerImage()
}

/**
 * Save the cropped avatar
 */
const saveAvatar = async () => {
    if (!selectedFile.value || !previewContainer.value || !previewImage.value) return

    isSaving.value = true

    try {
        // Create a canvas to crop the image
        const canvas = document.createElement('canvas')
        const ctx = canvas.getContext('2d')

        // Set canvas size to the container size (this will be our crop size)
        const containerWidth = previewContainer.value.offsetWidth
        const containerHeight = previewContainer.value.offsetHeight
        canvas.width = containerWidth
        canvas.height = containerHeight

        // Calculate the scaled image dimensions
        const scaledWidth = previewImage.value.naturalWidth * zoomLevel.value
        const scaledHeight = previewImage.value.naturalHeight * zoomLevel.value

        // Draw only the visible portion of the image on the canvas
        ctx.drawImage(
            previewImage.value,
            -cropperPosition.value.x / zoomLevel.value,
            -cropperPosition.value.y / zoomLevel.value,
            containerWidth / zoomLevel.value,
            containerHeight / zoomLevel.value,
            0, 0, containerWidth, containerHeight
        )

        // Convert canvas to blob
        const blob = await new Promise(resolve => {
            canvas.toBlob(resolve, 'image/jpeg', 0.9)
        })

        // Check if we're in development mode
        if (isDevelopmentMode.value) {
            // In development mode, just create a data URL and emit it
            const reader = new FileReader()
            reader.onload = (e) => {
                const avatarUrl = e.target.result
                emit('update', avatarUrl)
                emit('close')
                isSaving.value = false
            }
            reader.readAsDataURL(blob)
            return
        }

        // Create form data for upload
        const formData = new FormData()
        formData.append('avatar', blob, 'avatar.jpg')

        // Upload to server
        const response = await fetch(`${apiUrl}/api/avatar.php`, {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })

        const data = await response.json()

        if (data.status === 'success') {
            emit('update', data.avatarUrl)
            emit('close')
        } else {
            console.error('Failed to upload avatar:', data.message)
            alert('Failed to upload avatar. Please try again.')
        }
    } catch (err) {
        console.error('Error saving avatar:', err)
        alert('An error occurred while saving your avatar. Please try again.')
    } finally {
        isSaving.value = false
    }
}

// Clean up on component unmount
onBeforeUnmount(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
    }

    // Remove any event listeners
    document.removeEventListener('mousemove', dragImage)
    document.removeEventListener('touchmove', dragImage)
    document.removeEventListener('mouseup', endDrag)
    document.removeEventListener('touchend', endDrag)
})
</script>

<style scoped>
.avatar-uploader {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1100;
}

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
}

.modal-content {
    width: 100%;
    max-width: 500px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
}

.modal-header h3 {
    margin: 0;
    color: #333;
}

.close-button {
    background: none;
    border: none;
    font-size: 24px;
    color: #999;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.modal-body {
    padding: 20px;
}

.upload-area {
    position: relative;
    height: 300px;
    border: 2px dashed #ddd;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.upload-area:hover {
    border-color: var(--primary-color);
}

.upload-prompt {
    text-align: center;
    color: #666;
}

.upload-icon {
    font-size: 48px;
    margin-bottom: 10px;
}

.upload-hint {
    font-size: 12px;
    color: #999;
    margin-top: 10px;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.preview-area {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.preview-container {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    background-color: #f5f5f5;
}

.preview-image {
    position: absolute;
    transform-origin: center;
    user-select: none;
    cursor: move;
}

.preview-controls {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center;
}

.zoom-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    max-width: 300px;
}

.zoom-slider {
    flex: 1;
}

.zoom-slider input {
    width: 100%;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 15px 20px;
    border-top: 1px solid #eee;
}
</style>