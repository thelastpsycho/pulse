<div class="max-w-4xl">
    <form wire:submit="save">
        <div class="card">
            <div class="p-6 space-y-8">
                <!-- Issue Details Section -->
                <div>
                    <h3 class="text-lg font-semibold text-text mb-4 pb-2 border-b border-border">Issue Details</h3>
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <x-input-label for="title" value="Title *" />
                            <x-text-input
                                id="title"
                                wire:model="title"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                placeholder="Brief description of the issue"
                            />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea
                                id="description"
                                wire:model="description"
                                rows="4"
                                class="mt-1 block w-full bg-surface-2 border border-border text-text placeholder-muted rounded-lg focus:border-primary focus:ring-primary"
                                placeholder="Detailed description of the issue..."
                            ></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Recovery Action -->
                        <div>
                            <x-input-label for="recovery" value="Recovery Action Taken" />
                            <textarea
                                id="recovery"
                                wire:model="recovery"
                                rows="3"
                                class="mt-1 block w-full bg-surface-2 border border-border text-text placeholder-muted rounded-lg focus:border-primary focus:ring-primary"
                                placeholder="Describe the recovery actions taken..."
                            ></textarea>
                            <x-input-error :messages="$errors->get('recovery')" class="mt-2" />
                        </div>

                        <!-- Priority & Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Priority -->
                            <div>
                                <x-input-label for="priority" value="Priority *" />
                                <select
                                    id="priority"
                                    wire:model="priority"
                                    class="mt-1 block w-full bg-surface-2 border border-border text-text rounded-lg focus:border-primary focus:ring-primary"
                                    required
                                >
                                    <option value="">Select priority</option>
                                    @foreach($this->priorities as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                            </div>

                            <!-- Location -->
                            <div>
                                <x-input-label for="location" value="Location" />
                                <x-text-input
                                    id="location"
                                    wire:model="location"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g., Room 302, Front Desk, etc."
                                />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guest Details Section -->
                <div>
                    <h3 class="text-lg font-semibold text-text mb-4 pb-2 border-b border-border">Guest Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Guest Name -->
                        <div>
                            <x-input-label for="name" value="Guest Name" />
                            <x-text-input
                                id="name"
                                wire:model="name"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Guest name"
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Room Number -->
                        <div>
                            <x-input-label for="room_number" value="Room Number" />
                            <x-text-input
                                id="room_number"
                                wire:model="room_number"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., 302"
                            />
                            <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                        </div>

                        <!-- Nationality -->
                        <div>
                            <x-input-label for="nationality" value="Nationality" />
                            <x-text-input
                                id="nationality"
                                wire:model="nationality"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., Canadian"
                            />
                            <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                        </div>

                        <!-- Contact -->
                        <div>
                            <x-input-label for="contact" value="Contact" />
                            <x-text-input
                                id="contact"
                                wire:model="contact"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Phone number or email"
                            />
                            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                        </div>

                        <!-- Check-in Date -->
                        <div>
                            <x-input-label for="checkin_date" value="Check-in Date" />
                            <x-text-input
                                id="checkin_date"
                                wire:model="checkin_date"
                                type="date"
                                class="mt-1 block w-full"
                            />
                            <x-input-error :messages="$errors->get('checkin_date')" class="mt-2" />
                        </div>

                        <!-- Check-out Date -->
                        <div>
                            <x-input-label for="checkout_date" value="Check-out Date" />
                            <x-text-input
                                id="checkout_date"
                                wire:model="checkout_date"
                                type="date"
                                class="mt-1 block w-full"
                            />
                            <x-input-error :messages="$errors->get('checkout_date')" class="mt-2" />
                        </div>

                        <!-- Issue Date -->
                        <div>
                            <x-input-label for="issue_date" value="Issue Date" />
                            <x-text-input
                                id="issue_date"
                                wire:model="issue_date"
                                type="date"
                                class="mt-1 block w-full"
                            />
                            <x-input-error :messages="$errors->get('issue_date')" class="mt-2" />
                        </div>

                        <!-- Source -->
                        <div>
                            <x-input-label for="source" value="Source" />
                            <x-text-input
                                id="source"
                                wire:model="source"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="e.g., OTA, Direct Booking"
                            />
                            <x-input-error :messages="$errors->get('source')" class="mt-2" />
                        </div>

                        <!-- Recovery Cost -->
                        <div>
                            <x-input-label for="recovery_cost" value="Recovery Cost" />
                            <x-text-input
                                id="recovery_cost"
                                wire:model="recovery_cost"
                                type="number"
                                min="0"
                                step="1"
                                class="mt-1 block w-full"
                                placeholder="0"
                            />
                            <x-input-error :messages="$errors->get('recovery_cost')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Training (full width) -->
                    <div class="mt-6">
                        <x-input-label for="training" value="Training Required" />
                        <textarea
                            id="training"
                            wire:model="training"
                            rows="2"
                            class="mt-1 block w-full bg-surface-2 border border-border text-text placeholder-muted rounded-lg focus:border-primary focus:ring-primary"
                            placeholder="Any training needed to prevent recurrence..."
                        ></textarea>
                        <x-input-error :messages="$errors->get('training')" class="mt-2" />
                    </div>
                </div>

                <!-- Classification Section -->
                <div>
                    <h3 class="text-lg font-semibold text-text mb-4 pb-2 border-b border-border">Classification</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Departments -->
                        <div>
                            <x-input-label for="departments" value="Departments *" />
                            <div class="mt-2 space-y-2 max-h-48 overflow-y-auto">
                                @foreach($this->departments as $id => $name)
                                    <label class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            wire:model.live="department_ids"
                                            value="{{ $id }}"
                                            class="rounded border-border bg-surface-2 text-primary focus:ring-primary"
                                        />
                                        <span class="text-sm text-text">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('department_ids')" class="mt-2" />
                            <p class="mt-1 text-xs text-muted">Select at least one department</p>
                        </div>

                        <!-- Issue Types -->
                        <div>
                            <x-input-label for="issue_types" value="Issue Types *" />
                            <div class="mt-2 space-y-2 max-h-48 overflow-y-auto">
                                @foreach($this->issueTypes as $id => $name)
                                    <label class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            wire:model.live="issue_type_ids"
                                            value="{{ $id }}"
                                            class="rounded border-border bg-surface-2 text-primary focus:ring-primary"
                                        />
                                        <span class="text-sm text-text">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('issue_type_ids')" class="mt-2" />
                            <p class="mt-1 text-xs text-muted">Select at least one issue type</p>
                        </div>
                    </div>
                </div>

                <!-- Assignment Section -->
                <div>
                    <h3 class="text-lg font-semibold text-text mb-4 pb-2 border-b border-border">Assignment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Assigned To -->
                        <div>
                            <x-input-label for="assigned_to" value="Assigned To" />
                            <select
                                id="assigned_to"
                                wire:model="assigned_to"
                                class="mt-1 block w-full bg-surface-2 border border-border text-text rounded-lg focus:border-primary focus:ring-primary"
                            >
                                <option value="">Unassigned</option>
                                @foreach($this->users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-surface-2 border-t border-border flex items-center justify-end gap-3">
                <button type="button" wire:click="cancel" class="btn btn-secondary">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ $isEditing ? 'Update Issue' : 'Create Issue' }}
                </button>
            </div>
        </div>
    </form>
</div>
