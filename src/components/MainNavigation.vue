<template>
    <div class="nav-container" :class="{ 'nav-visible': isNavVisible }">
        <transition name="slide">
            <div class="nav-menu" v-if="isNavVisible">
                <h3>Navigation</h3>
                <ul>
                    <li>
                        <a href="#" @click.prevent="navigateTo('home')"
                            :class="{ active: activeSection === 'home' }">Home</a>
                    </li>
                    <li>
                        <a href="#" @click.prevent="navigateTo('selected')"
                            :class="{ active: activeSection === 'selected' }">Selected Chords</a>
                    </li>
                    <li>
                        <a href="#" @click.prevent="navigateTo('all')" :class="{ active: activeSection === 'all' }">All
                            Chords</a>
                    </li>
                    <li>
                        <a href="#" @click.prevent="navigateTo('about')"
                            :class="{ active: activeSection === 'about' }">About</a>
                    </li>
                </ul>
            </div>
        </transition>
        <button class="nav-button" @click="toggleNav" :class="{ active: isNavVisible }"
            aria-label="Toggle navigation menu">
            <span v-if="isNavVisible">×</span>
            <span v-else>☰</span>
        </button>
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

.nav-button {
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

.nav-button:hover {
    background-color: #1976d2;
}

.nav-button.active {
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
</style>