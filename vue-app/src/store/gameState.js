import { reactive } from 'vue'

export const gameState = reactive({
    unlocked: {
        home: true,           // Home is always unlocked
        bank: false,
        supermarket: false,
        fastfood: false,
        department: false,
        onlineStore: false,
    }
})
