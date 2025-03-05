<template>
    <div class="nav-container" :class="{ 'nav-visible': isNavVisible }">
        <transition name="slide">
            <div class="nav-menu" v-if="isNavVisible">
                <h3>Navigation</h3>
                <ul>
                    <li>
                        <router-link to="/" @click="closeNav" :class="{ active: activeSection === 'home' }">
                            Home
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/selected" @click="closeNav" :class="{ active: activeSection === 'selected' }">
                            Selected Chords
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/all" @click="closeNav" :class="{ active: activeSection === 'all' }">
                            All Chords
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/about" @click="closeNav" :class="{ active: activeSection === 'about' }">
                            About
                        </router-link>
                    </li>
                </ul>
            </div>
        </transition>
        <div class="nav-buttons">
            <button class="btn btn-icon btn-primary nav-button" @click="toggleNav" :class="{ active: isNavVisible }"
                aria-label="Toggle navigation menu">
                <span v-if="isNavVisible">×</span>
                <span v-else>☰</span>
            </button>

            <!-- Slot for user avatar menu -->
            <div class="avatar-menu-slot">
                <slot name="avatar-menu"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue'

const props = defineProps({
    activeSection: {
        type: String,
        default: 'home'
    }
})

const emit = defineEmits(['navigate'])

// State
const isNavVisible = ref(false)

// Methods
const toggleNav = () => {
    isNavVisible.value = !isNavVisible.value
}

const closeNav = () => {
    isNavVisible.value = false
}

const navigateTo = (section) => {
    emit('navigate', section)
    isNavVisible.value = false
}
</script>

<style scoped>
.nav-container {
    position: relative;
    z-index: 1000;
}

.nav-menu {
    position: absolute;
    top: 0;
    right: 50px;
    width: 230px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    padding: 15px;
}

.nav-menu h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #333;
    font-size: 1.2rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}

.nav-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-menu li {
    margin-bottom: 10px;
}

.nav-menu a {
    display: block;
    padding: 8px 12px;
    color: #555;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.nav-menu a:hover {
    background-color: #f5f5f5;
    color: #2196f3;
}

.nav-menu a.active {
    background-color: #e3f2fd;
    color: #1976d2;
    font-weight: 500;
}

.nav-buttons {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.nav-button.active {
    background-color: var(--danger-color);
    transform: rotate(90deg);
}

.nav-button.active:hover {
    background-color: var(--danger-hover);
}

.avatar-menu-slot {
    margin-top: 10px;
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
</style>