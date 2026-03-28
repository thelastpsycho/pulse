// Kanban board drag-and-drop functionality using SortableJS
import Sortable from 'sortablejs';

export function kanbanBoard() {
    return {
        sortableInstances: [],

        initKanban() {
            this.$nextTick(() => {
                this.initializeSortable();
            });
        },

        initializeSortable() {
            const columns = document.querySelectorAll('.kanban-cards');

            columns.forEach((column) => {
                const sortable = new Sortable(column, {
                    group: 'kanban', // Allow dragging between columns
                    animation: 200,   // Smooth animation duration
                    delay: 0,         // No delay for mouse
                    delayOnTouchOnly: true,
                    touchStartThreshold: 44, // Minimum touch target (px)
                    ghostClass: 'kanban-ghost', // Class for dragged element
                    chosenClass: 'kanban-chosen', // Class for selected element
                    dragClass: 'kanban-drag', // Class for dragging element
                    dataIdAttr: 'data-issue-id',

                    onStart: (evt) => {
                        // Add dragging styles
                        evt.item.classList.add('opacity-50', 'scale-105', 'shadow-2xl');

                        // Emit drag start event to Livewire
                        const issueId = evt.item.getAttribute('data-issue-id');
                        if (window.livewire) {
                            window.livewire.emit('dragStart', { issueId });
                        }
                    },

                    onEnd: (evt) => {
                        // Remove dragging styles
                        evt.item.classList.remove('opacity-50', 'scale-105', 'shadow-2xl');

                        // Get new column
                        const newColumn = evt.to.getAttribute('data-column');
                        const issueId = evt.item.getAttribute('data-issue-id');

                        // Emit drag end event to Livewire
                        if (window.livewire) {
                            window.livewire.emit('dragEnd', { issueId });

                            // If moved to different column, update status
                            if (evt.from !== evt.to) {
                                const statusMap = {
                                    'open': 'open',
                                    'in-progress': 'in_progress',
                                    'closed': 'closed'
                                };

                                const newStatus = statusMap[newColumn];
                                if (newStatus) {
                                    window.livewire.emit('updateIssueStatus', {
                                        issueId: issueId,
                                        newStatus: newStatus
                                    });

                                    // Add success flash animation
                                    evt.item.classList.add('success-flash');
                                    setTimeout(() => {
                                        evt.item.classList.remove('success-flash');
                                    }, 300);
                                }
                            }
                        }
                    },

                    // Keyboard accessibility
                    onMove: (evt) => {
                        // Prevent dropping on empty column (keep visual feedback)
                        return true;
                    }
                });

                this.sortableInstances.push(sortable);
            });
        },

        // Clean up Sortable instances when component is destroyed
        destroy() {
            this.sortableInstances.forEach(instance => {
                instance.destroy();
            });
            this.sortableInstances = [];
        }
    };
}
