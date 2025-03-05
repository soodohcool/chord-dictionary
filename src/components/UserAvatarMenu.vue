<template>
    <div class="avatar-menu-container" ref="containerRef">
        <button class="avatar-button" @click="toggleMenu" :style="avatarStyle" aria-label="User menu">
            <span v-if="!user.avatar">{{ userInitial }}</span>
        </button>

        <transition name="fade">
            <div v-if="isMenuOpen" class="avatar-menu">
                <div class="menu-header">
                    <div class="user-info">
                        <div class="avatar-small" :style="avatarStyle">
                            <span v-if="!user.avatar">{{ userInitial }}</span>
                        </div>
                        <div class="user-details">
                            <h4>{{ user.username }}</h4>
                            <p>{{ user.email }}</p>
                        </div>
                    </div>
                </div>

                <div class="menu-items">
                    <button class="menu-item" @click="handleViewProfile">
                        <span class="icon">👤</span> View Profile
                    </button>
                    <router-link to="/selected" class="menu-item" @click="closeMenu">
                        <span class="icon">🎵</span> My Saved Chords
                    </router-link>
                    <button class="menu-item" @click="handleUpdateAvatar">
                        <span class="icon">📷</span> Update Avatar
                    </button>
                    <div class="menu-divider"></div>
                    <button class="menu-item logout" @click="handleLogout">
                        <span class="icon">🚪</span> Logout
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

/**
 * User avatar menu component that displays a circular avatar button and a dropdown menu
 * 
 * @prop {Object} user - User data object
 * @emits logout - Emitted when user logs out
 * @emits view-profile - Emitted when user clicks to view their profile
 * @emits update-avatar - Emitted when user wants to update their avatar
 */
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['logout', 'view-profile', 'update-avatar'])

// State
const isMenuOpen = ref(false)
const containerRef = ref(null)

// Computed properties
const userInitial = computed(() => {
    return props.user.username ? props.user.username.charAt(0).toUpperCase() : '?'
})

const avatarStyle = computed(() => {
    if (props.user.avatar) {
        return {
            backgroundImage: `url(${props.user.avatar})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center'
        }
    }
    return {}
})

// Methods
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
    isMenuOpen.value = false
}

const handleLogout = () => {
    closeMenu()
    emit('logout')
}

const handleViewProfile = () => {
    closeMenu()
    emit('view-profile')
}

const handleUpdateAvatar = () => {
    closeMenu()
    emit('update-avatar')
}

/**
 * Handle click outside the component
 * 
 * @param {Event} event - Click event
 */
const handleClickOutside = (event) => {
    if (containerRef.value && !containerRef.value.contains(event.target)) {
        isMenuOpen.value = false
    }
}

// Add event listener for clicks outside the component
onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

// Remove event listener when component is unmounted
onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.avatar-menu-container {
    position: relative;
    z-index: 900;
}

.avatar-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #2196f3;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    overflow: hidden;
}

.avatar-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.avatar-menu {
    position: absolute;
    top: 50px;
    right: 0;
    width: 250px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.menu-header {
    padding: 15px;
    background-color: #f5f5f5;
    border-bottom: 1px solid #eee;
}

.user-info {
    display: flex;
    align-items: center;
}

.avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    margin-right: 10px;
    overflow: hidden;
}

.user-details {
    flex: 1;
}

.user-details h4 {
    margin: 0 0 3px 0;
    font-size: 14px;
    color: #333;
}

.user-details p {
    margin: 0;
    font-size: 12px;
    color: #666;
}

.menu-items {
    padding: 8px 0;
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: #555;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.2s ease;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.menu-item:hover {
    background-color: var(--light-hover);
}

.menu-item .icon {
    margin-right: 10px;
    font-size: 16px;
}

.menu-divider {
    height: 1px;
    background-color: #eee;
    margin: 8px 0;
}

.menu-item.logout {
    color: var(--danger-color);
}

.menu-item.logout:hover {
    background-color: #ffebee;
}

/* Fade transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>