<template>
    <div class="user-profile">
        <div class="profile-header">
            <h2>My Profile</h2>
            <div class="header-actions">
                <button class="logout-button" @click="handleLogout">Logout</button>
                <button class="close-button" @click="$emit('close')">×</button>
            </div>
        </div>

        <div class="user-info">
            <div class="avatar">{{ userInitial }}</div>
            <div class="details">
                <h3>{{ user.username }}</h3>
                <p>{{ user.email }}</p>
            </div>
        </div>

        <div class="saved-progressions">
            <div class="section-header">
                <h3>My Saved Progressions</h3>
                <button class="new-progression-button" @click="showSaveForm = true">
                    <span class="icon">+</span> New Progression
                </button>
            </div>

            <div v-if="isLoading" class="loading">Loading progressions...</div>

            <div v-else-if="progressions.length === 0" class="empty-state">
                <p>You haven't saved any chord progressions yet.</p>
                <button @click="showSaveForm = true">Save Current Progression</button>
            </div>

            <div v-else class="progressions-list">
                <div v-for="progression in progressions" :key="progression.id" class="progression-item">
                    <div class="progression-info">
                        <h4>{{ progression.name }}</h4>
                        <div class="progression-chords">
                            <span v-for="(chord, index) in progression.chords" :key="index" class="chord-tag">
                                {{ chord }}
                            </span>
                        </div>
                        <div class="progression-meta">
                            <span class="date">{{ formatDate(progression.updated_at) }}</span>
                            <span class="visibility" :class="{ public: progression.is_public }">
                                {{ progression.is_public ? 'Public' : 'Private' }}
                            </span>
                        </div>
                    </div>
                    <div class="progression-actions">
                        <button class="load-button" @click="loadProgression(progression)">Load</button>
                        <button class="edit-button" @click="editProgression(progression)">Edit</button>
                        <button class="delete-button" @click="confirmDelete(progression)">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save/Edit Progression Form -->
        <div v-if="showSaveForm" class="modal-overlay" @click.self="cancelSaveForm">
            <div class="modal-content">
                <h3>{{ editMode ? 'Edit Progression' : 'Save Progression' }}</h3>

                <div class="form-group">
                    <label for="progression-name">Name</label>
                    <input id="progression-name" v-model="progressionForm.name" type="text" required
                        placeholder="Enter a name for this progression" />
                </div>

                <div class="form-group">
                    <label>Chords</label>
                    <div class="chords-preview">
                        <span v-for="(chord, index) in progressionForm.chords" :key="index" class="chord-tag">
                            {{ chord }}
                        </span>
                    </div>
                </div>

                <div class="form-group checkbox">
                    <input id="is-public" v-model="progressionForm.isPublic" type="checkbox" />
                    <label for="is-public">Make this progression public</label>
                </div>

                <div class="form-actions">
                    <button class="cancel-button" @click="cancelSaveForm">Cancel</button>
                    <button class="save-button" @click="saveProgression" :disabled="isSaving || !progressionForm.name">
                        {{ isSaving ? 'Saving...' : (editMode ? 'Update' : 'Save') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteConfirm" class="modal-overlay" @click.self="showDeleteConfirm = false">
            <div class="modal-content delete-confirm">
                <h3>Delete Progression</h3>
                <p>Are you sure you want to delete "<strong>{{ progressionToDelete?.name }}</strong>"?</p>
                <p class="warning">This action cannot be undone.</p>

                <div class="form-actions">
                    <button class="cancel-button" @click="showDeleteConfirm = false">Cancel</button>
                    <button class="delete-button" @click="deleteProgression" :disabled="isDeleting">
                        {{ isDeleting ? 'Deleting...' : 'Delete' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, inject } from 'vue'

/**
 * User profile component for displaying user info and saved progressions
 * 
 * @prop {Object} user - User data object
 * @prop {Array} currentChords - Currently selected chords in the app
 * @emits logout - Emitted when user logs out
 * @emits load-progression - Emitted when user loads a saved progression
 */

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    currentChords: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['logout', 'load-progression', 'close'])

// Injected values
const isDevelopmentMode = inject('isDevelopmentMode', ref(false))
const apiUrl = inject('apiUrl', '/backend')

// State
const isLoading = ref(true)
const isSaving = ref(false)
const isDeleting = ref(false)
const progressions = ref([])
const showSaveForm = ref(false)
const showDeleteConfirm = ref(false)
const editMode = ref(false)
const progressionToDelete = ref(null)

// Form data
const progressionForm = reactive({
    id: null,
    name: '',
    chords: [],
    isPublic: false
})

// Computed properties
const userInitial = computed(() => {
    return props.user.username ? props.user.username.charAt(0).toUpperCase() : '?'
})

// Lifecycle hooks
onMounted(async () => {
    await fetchProgressions()
})

/**
 * Fetch user's saved progressions
 */
const fetchProgressions = async () => {
    isLoading.value = true

    try {
        // Check if we're in development mode without a backend
        if (isDevelopmentMode.value && !window.location.hostname.includes('localhost')) {
            // Simulate progressions in development
            setTimeout(() => {
                progressions.value = [
                    {
                        id: 1,
                        name: 'Sample Progression 1',
                        chords: ['C', 'G', 'Am', 'F'],
                        is_public: true,
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString()
                    },
                    {
                        id: 2,
                        name: 'Sample Progression 2',
                        chords: ['Dm', 'G', 'C', 'Am'],
                        is_public: false,
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString()
                    }
                ]
                isLoading.value = false
            }, 500)
            return
        }

        // API endpoint URL - adjust as needed
        const apiEndpoint = `${apiUrl}/api/progressions.php?user=true`

        const response = await fetch(apiEndpoint, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'include' // Include cookies in the request
        })

        // Check if response is JSON
        const contentType = response.headers.get('content-type')
        if (!contentType || !contentType.includes('application/json')) {
            console.warn('API returned non-JSON response')
            progressions.value = []
            return
        }

        const data = await response.json()

        if (data.status === 'success') {
            progressions.value = data.progressions || []
        } else {
            console.error('Failed to fetch progressions:', data.message)
            progressions.value = []
        }
    } catch (err) {
        console.error('Error fetching progressions:', err)
        progressions.value = []
    } finally {
        isLoading.value = false
    }
}

/**
 * Format date for display
 * 
 * @param {string} dateString - Date string from API
 * @returns {string} Formatted date
 */
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

/**
 * Handle user logout
 */
const handleLogout = async () => {
    try {
        // Check if we're in development mode without a backend
        if (isDevelopmentMode.value && !window.location.hostname.includes('localhost')) {
            // Simulate logout in development
            emit('logout')
            return
        }

        // API endpoint URL - adjust as needed
        const apiEndpoint = `${apiUrl}/api/logout.php`

        await fetch(apiEndpoint, {
            method: 'POST',
            credentials: 'include' // Include cookies in the request
        })

        // Emit logout event
        emit('logout')
    } catch (err) {
        console.error('Logout error:', err)
    }
}

/**
 * Load a saved progression
 * 
 * @param {Object} progression - Progression to load
 */
const loadProgression = (progression) => {
    emit('load-progression', progression.chords)
}

/**
 * Open edit form for a progression
 * 
 * @param {Object} progression - Progression to edit
 */
const editProgression = (progression) => {
    editMode.value = true
    progressionForm.id = progression.id
    progressionForm.name = progression.name
    progressionForm.chords = [...progression.chords]
    progressionForm.isPublic = progression.is_public
    showSaveForm.value = true
}

/**
 * Open save form for current progression
 */
const openSaveForm = () => {
    editMode.value = false
    progressionForm.id = null
    progressionForm.name = ''
    progressionForm.chords = [...props.currentChords]
    progressionForm.isPublic = false
    showSaveForm.value = true
}

/**
 * Cancel save form
 */
const cancelSaveForm = () => {
    showSaveForm.value = false
    editMode.value = false
    progressionForm.id = null
    progressionForm.name = ''
    progressionForm.chords = []
    progressionForm.isPublic = false
}

/**
 * Save or update a progression
 */
const saveProgression = async () => {
    if (!progressionForm.name) return

    isSaving.value = true

    try {
        // Check if we're in development mode without a backend
        if (isDevelopmentMode.value && !window.location.hostname.includes('localhost')) {
            // Simulate saving in development
            setTimeout(() => {
                const newId = progressions.value.length + 1
                if (editMode.value) {
                    // Update existing progression
                    const index = progressions.value.findIndex(p => p.id === progressionForm.id)
                    if (index !== -1) {
                        progressions.value[index] = {
                            ...progressions.value[index],
                            name: progressionForm.name,
                            chords: [...progressionForm.chords],
                            is_public: progressionForm.isPublic,
                            updated_at: new Date().toISOString()
                        }
                    }
                } else {
                    // Add new progression
                    progressions.value.push({
                        id: newId,
                        name: progressionForm.name,
                        chords: [...progressionForm.chords],
                        is_public: progressionForm.isPublic,
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString()
                    })
                }

                // Close form
                cancelSaveForm()
                isSaving.value = false
            }, 500)
            return
        }

        // API endpoint URL - adjust as needed
        const apiEndpoint = `${apiUrl}/api/progressions.php`

        const method = editMode.value ? 'PUT' : 'POST'
        const body = {
            name: progressionForm.name,
            chords: progressionForm.chords,
            is_public: progressionForm.isPublic
        }

        // Add ID for updates
        if (editMode.value) {
            body.id = progressionForm.id
        }

        const response = await fetch(apiEndpoint, {
            method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body),
            credentials: 'include' // Include cookies in the request
        })

        const data = await response.json()

        if (data.status === 'success') {
            // Refresh progressions list
            await fetchProgressions()

            // Close form
            cancelSaveForm()
        } else {
            console.error('Failed to save progression:', data.message)
        }
    } catch (err) {
        console.error('Error saving progression:', err)
    } finally {
        isSaving.value = false
    }
}

/**
 * Show delete confirmation
 * 
 * @param {Object} progression - Progression to delete
 */
const confirmDelete = (progression) => {
    progressionToDelete.value = progression
    showDeleteConfirm.value = true
}

/**
 * Delete a progression
 */
const deleteProgression = async () => {
    if (!progressionToDelete.value) return

    isDeleting.value = true

    try {
        // Check if we're in development mode without a backend
        if (isDevelopmentMode.value && !window.location.hostname.includes('localhost')) {
            // Simulate deletion in development
            setTimeout(() => {
                progressions.value = progressions.value.filter(p => p.id !== progressionToDelete.value.id)
                showDeleteConfirm.value = false
                progressionToDelete.value = null
                isDeleting.value = false
            }, 500)
            return
        }

        // API endpoint URL - adjust as needed
        const apiEndpoint = `${apiUrl}/api/progressions.php?id=${progressionToDelete.value.id}`

        const response = await fetch(apiEndpoint, {
            method: 'DELETE',
            credentials: 'include' // Include cookies in the request
        })

        const data = await response.json()

        if (data.status === 'success') {
            // Refresh progressions list
            await fetchProgressions()

            // Close confirmation
            showDeleteConfirm.value = false
            progressionToDelete.value = null
        } else {
            console.error('Failed to delete progression:', data.message)
        }
    } catch (err) {
        console.error('Error deleting progression:', err)
    } finally {
        isDeleting.value = false
    }
}
</script>

<style scoped>
.user-profile {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 450px;
    /* Adjusted width to account for padding */
    background-color: #f9f9f9;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    z-index: 1000;
    padding: 20px;
}

.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.profile-header h2 {
    margin: 0;
    color: #333;
}

.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.logout-button {
    padding: 8px 16px;
    background-color: #f5f5f5;
    color: #555;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.logout-button:hover {
    background-color: #e0e0e0;
}

.close-button {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #f5f5f5;
    color: #555;
    font-size: 20px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.close-button:hover {
    background-color: #e0e0e0;
}

.user-info {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #2196f3;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    margin-right: 20px;
}

.details h3 {
    margin: 0 0 5px 0;
    color: #333;
}

.details p {
    margin: 0;
    color: #666;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.section-header h3 {
    margin: 0;
    color: #333;
}

.new-progression-button {
    display: flex;
    align-items: center;
    padding: 8px 16px;
    background-color: #2196f3;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.new-progression-button:hover {
    background-color: #1976d2;
}

.new-progression-button .icon {
    margin-right: 5px;
    font-size: 16px;
    font-weight: bold;
}

.loading {
    text-align: center;
    padding: 20px;
    color: #666;
}

.empty-state {
    text-align: center;
    padding: 30px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.empty-state p {
    margin-bottom: 15px;
    color: #666;
}

.empty-state button {
    padding: 10px 20px;
    background-color: #2196f3;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.empty-state button:hover {
    background-color: #1976d2;
}

.progressions-list {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
}

.progression-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.progression-info {
    flex: 1;
}

.progression-info h4 {
    margin: 0 0 10px 0;
    color: #333;
}

.progression-chords {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 10px;
}

.chord-tag {
    display: inline-block;
    padding: 4px 8px;
    background-color: #e3f2fd;
    color: #1976d2;
    border-radius: 16px;
    font-size: 12px;
}

.progression-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: #777;
}

.visibility {
    padding: 2px 6px;
    background-color: #f5f5f5;
    border-radius: 4px;
}

.visibility.public {
    background-color: #e8f5e9;
    color: #388e3c;
}

.progression-actions {
    display: flex;
    gap: 8px;
}

.progression-actions button {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.load-button {
    background-color: #2196f3;
    color: white;
}

.load-button:hover {
    background-color: #1976d2;
}

.edit-button {
    background-color: #f5f5f5;
    color: #555;
}

.edit-button:hover {
    background-color: #e0e0e0;
}

.delete-button {
    background-color: #ffebee;
    color: #d32f2f;
}

.delete-button:hover {
    background-color: #ffcdd2;
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
    max-width: 540px;
    /* Increased from 500px to account for padding with box-sizing: border-box */
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
    height: 40px;
    /* Explicitly set height to account for box-sizing */
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
    height: 40px;
    /* Explicitly set height to account for box-sizing */
}

.save-button {
    padding: 8px 16px;
    background-color: #2196f3;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    height: 40px;
    /* Explicitly set height to account for box-sizing */
}

.save-button:disabled {
    background-color: #b0bec5;
    cursor: not-allowed;
}

/* Delete confirmation */
.delete-confirm p {
    margin-bottom: 10px;
    color: #555;
}

.delete-confirm .warning {
    color: #d32f2f;
    font-size: 14px;
}
</style>