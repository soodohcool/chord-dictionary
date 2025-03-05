<script setup>
import { ref, computed, onMounted, provide } from 'vue'
import AuthForm from './components/AuthForm.vue'
import UserProfile from './components/UserProfile.vue'
import UserAvatarMenu from './components/UserAvatarMenu.vue'
import AvatarUploader from './components/AvatarUploader.vue'

// Environment variables
const apiUrl = import.meta.env.VITE_API_URL || '/backend'
const devModeEnabled = (import.meta.env.VITE_DEV_MODE === 'true')

// State
const isLoading = ref(true)
const isLoggedIn = ref(false)
const currentUser = ref(null)
const showAuthForm = ref(false)
const showUserProfile = ref(false)
const showAvatarUploader = ref(false)
const currentChords = ref([])
const isDevelopmentMode = ref(devModeEnabled && import.meta.env.MODE === 'development')

// Computed properties
const userInitial = computed(() => {
  if (!currentUser.value || !currentUser.value.username) return '?'
  return currentUser.value.username.charAt(0).toUpperCase()
})

// Computed property for development mode check
const isDevMode = computed(() => {
  return import.meta.env.MODE === 'development'
})

// Provide authentication state to child components
provide('isLoggedIn', isLoggedIn)
provide('currentUser', currentUser)
provide('isDevelopmentMode', isDevelopmentMode)
provide('apiUrl', apiUrl)

// Lifecycle hooks
onMounted(async () => {
  await checkAuthStatus()
  isLoading.value = false
})

/**
 * Check if user is already logged in
 */
const checkAuthStatus = async () => {
  try {
    // Check if we're in development mode and skip auth check
    if (isDevelopmentMode.value && !window.location.hostname.includes('localhost')) {
      console.log('Skipping auth check in development mode')
      isLoading.value = false
      return
    }

    // In development mode, we can simulate a logged-in user for testing
    if (isDevelopmentMode.value && localStorage.getItem('dev_user')) {
      try {
        const devUser = JSON.parse(localStorage.getItem('dev_user'))
        isLoggedIn.value = true
        currentUser.value = devUser
        console.log('Using development mode user:', devUser)
        return
      } catch (e) {
        console.error('Error parsing dev_user from localStorage:', e)
      }
    }

    const response = await fetch(`${apiUrl}/api/user.php`, {
      method: 'GET',
      credentials: 'include', // Include cookies in the request
      headers: {
        'Accept': 'application/json'
      }
    })

    // Check if response is JSON
    const contentType = response.headers.get('content-type')
    if (!contentType || !contentType.includes('application/json')) {
      console.warn('Auth API returned non-JSON response. User is not logged in.')
      isLoggedIn.value = false
      return
    }

    if (response.ok) {
      const data = await response.json()
      if (data.status === 'success') {
        isLoggedIn.value = true
        currentUser.value = data.user
      }
    }
  } catch (err) {
    console.error('Error checking auth status:', err)
    // Continue without authentication in case of error
    isLoggedIn.value = false
  } finally {
    isLoading.value = false
  }
}

/**
 * Handle user login
 * 
 * @param {Object} userData User data from login
 */
const handleLogin = (userData) => {
  isLoggedIn.value = true
  currentUser.value = userData
  showAuthForm.value = false

  // Store user data in localStorage for development mode
  if (isDevelopmentMode.value) {
    localStorage.setItem('dev_user', JSON.stringify(userData))
  }
}

/**
 * Handle user registration
 * 
 * @param {Object} userData User data from registration
 */
const handleRegister = (userData) => {
  isLoggedIn.value = true
  currentUser.value = userData
  showAuthForm.value = false

  // Store user data in localStorage for development mode
  if (isDevelopmentMode.value) {
    localStorage.setItem('dev_user', JSON.stringify(userData))
  }
}

/**
 * Handle user logout
 */
const handleLogout = () => {
  isLoggedIn.value = false
  currentUser.value = null
  showUserProfile.value = false

  // Remove user data from localStorage in development mode
  if (isDevelopmentMode.value) {
    localStorage.removeItem('dev_user')
  }
}

/**
 * Handle loading a saved progression
 * 
 * @param {Array} chords Array of chord names
 */
const handleLoadProgression = (chords) => {
  currentChords.value = [...chords]
  showUserProfile.value = false
}

/**
 * Update current chords (from child components)
 * 
 * @param {Array} chords Array of chord names
 */
const updateCurrentChords = (chords) => {
  currentChords.value = chords
}

/**
 * Handle avatar update
 * 
 * @param {string} avatarUrl URL of the new avatar
 */
const handleAvatarUpdate = (avatarUrl) => {
  if (currentUser.value) {
    currentUser.value = {
      ...currentUser.value,
      avatar: avatarUrl
    }

    // Update in localStorage for development mode
    if (isDevelopmentMode.value) {
      localStorage.setItem('dev_user', JSON.stringify(currentUser.value))
    }
  }
}

/**
 * Show the user profile
 */
const showProfile = () => {
  showUserProfile.value = true
}

// Methods
const toggleDevMode = () => {
  isDevelopmentMode.value = !isDevelopmentMode.value
  if (!isDevelopmentMode.value) {
    localStorage.removeItem('dev_user')
    isLoggedIn.value = false
    currentUser.value = null
  }
}
</script>

<template>
  <div class="app-container">
    <!-- Development Mode Toggle (only visible in development) -->
    <div v-if="isDevMode" class="dev-mode-toggle">
      <button class="btn btn-sm" @click="toggleDevMode"
        :class="{ 'btn-danger': isDevelopmentMode, 'btn-secondary': !isDevelopmentMode }">
        Dev Mode: {{ isDevelopmentMode ? 'ON' : 'OFF' }}
      </button>
    </div>

    <!-- Loading Spinner -->
    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
    </div>

    <!-- Main Content -->
    <div v-else>
      <!-- User Actions (top right) -->
      <div class="user-actions">
        <template v-if="isLoggedIn">
          <button class="avatar-button" @click="showUserProfile = !showUserProfile">
            <span v-if="!currentUser.avatar" class="user-initial">{{ userInitial }}</span>
            <img v-else :src="currentUser.avatar" alt="User avatar" class="user-avatar" />
          </button>
        </template>
        <template v-else>
          <button class="btn btn-primary" @click="showAuthForm = true">Login / Register</button>
        </template>
      </div>

      <!-- Router View (Main Content) -->
      <router-view v-slot="{ Component }">
        <component :is="Component" :is-logged-in="isLoggedIn" :current-user="currentUser"
          :current-chords="currentChords" @update:current-chords="updateCurrentChords"
          @login-required="showAuthForm = true">
          <template #avatar-menu>
            <UserAvatarMenu :user="currentUser" @logout="handleLogout" @view-profile="showProfile"
              @update-avatar="showAvatarUploader = true" />
          </template>
        </component>
      </router-view>

      <!-- Auth Form Modal -->
      <AuthForm v-if="showAuthForm" @close="showAuthForm = false" @login="handleLogin" @register="handleRegister" />

      <!-- User Profile Modal -->
      <UserProfile v-if="showUserProfile && isLoggedIn" :user="currentUser" :current-chords="currentChords"
        @close="showUserProfile = false" @logout="handleLogout" @load-progression="handleLoadProgression" />

      <!-- Avatar Uploader Modal -->
      <AvatarUploader v-if="showAvatarUploader && isLoggedIn" @close="showAvatarUploader = false"
        @update="handleAvatarUpdate" />
    </div>
  </div>
</template>

<style>
.app-container {
  position: relative;
  min-height: 100vh;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.user-actions {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 100;
}

.user-avatar {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.dev-mode-toggle {
  position: fixed;
  bottom: 20px;
  left: 20px;
  z-index: 1000;
}
</style>
