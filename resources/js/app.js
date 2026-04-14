import './bootstrap';
import { kanbanBoard } from './kanban';

// Initialize theme immediately (before DOM is ready)
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'light') {
    document.documentElement.classList.remove('dark');
    document.documentElement.classList.add('light');
} else {
    // Default to dark mode
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
}

// Register Alpine data functions
document.addEventListener('alpine:init', () => {
    if (typeof Alpine !== 'undefined') {
        Alpine.data('kanbanBoard', kanbanBoard);
    }
});
