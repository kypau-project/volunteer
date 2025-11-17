<div>
    @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="page-heading">
        <h3>List User</h3>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex gap-3 align-items-center grow">
                <!-- Search -->
                <input wire:model.live="search" type="search" class="form-control" placeholder="Search users...">

                <!-- Role Filter -->
                <select wire:model.live="roleFilter" class="form-select" style="width: auto;">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="coordinator">Coordinator</option>
                    <option value="volunteer">Volunteer</option>
                </select>

                <!-- Status Filter -->
                <select wire:model.live="statusFilter" class="form-select" style="width: auto;">
                    <option value="">Semua Status</option>
                    <option value="active">Active</option>
                    <option value="blocked">Blocked</option>
                </select>

                <div class="d-flex gap-2">
                    <!-- Create Button -->
                    <button wire:click="create" class="btn btn-primary rounded-circle shadow-sm" style="width: 42px; height: 42px;" title="Add New User">
                        <i class="bi-plus-lg"></i>
                    </button>

                    <div class="btn-group shadow-sm">
                        <!-- Export Button -->
                        <button wire:click="export" class="btn btn-success px-3" title="Export Users">
                            <i class="bi-download me-1"></i>
                            <span class="d-none d-md-inline">Export</span>
                        </button>
                        <!-- Import Button -->
                        <button onclick="document.getElementById('fileImport').click()" class="btn btn-info px-3" title="Import Users">
                            <i class="bi-upload me-1"></i>
                            <span class="d-none d-md-inline">Import</span>
                        </button>
                    </div>

                    <!-- File Name Display -->
                    <div id="fileNameDisplay" class="text-muted small d-none">
                        <i class="bi-file-earmark-text me-1"></i>
                        <span id="fileName"></span>
                        <button type="button" onclick="clearFileSelection()" class="btn btn-link btn-sm text-danger p-0 ms-2" title="Clear selection">
                            <i class="bi-x-circle"></i>
                        </button>
                    </div>
                </div>

                <!-- Hidden File Input -->
                <input type="file" id="fileImport" wire:model.live="importFile" class="d-none" accept=".xlsx,.xls" onchange="handleFileSelect(this)">

                <script>
                    function handleFileSelect(input) {
                        const fileNameDisplay = document.getElementById('fileNameDisplay');
                        const fileNameSpan = document.getElementById('fileName');

                        if (input.files && input.files[0]) {
                            fileNameSpan.textContent = input.files[0].name;
                            fileNameDisplay.classList.remove('d-none');

                            // Auto-submit import setelah file dipilih
                            setTimeout(() => {
                                document.getElementById('importSubmit').click();
                            }, 500);
                        }
                    }

                    function clearFileSelection() {
                        const fileInput = document.getElementById('fileImport');
                        const fileNameDisplay = document.getElementById('fileNameDisplay');

                        fileInput.value = '';
                        fileNameDisplay.classList.add('d-none');

                        // Trigger Livewire to clear the file
                        @this.call('resetImportFile');
                    }
                </script>

                <button wire:click="import" id="importSubmit" class="d-none"></button>
                @error('importFile') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                <span class="badge bg-primary ms-1">You</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                @if($user->is_blocked)
                                <span class="badge bg-danger">Blocked</span>
                                @else
                                <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button wire:click="view({{ $user->id }})" class="btn btn-sm btn-info">
                                        <i class="bi-eye"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    @if($user->role === 'admin')
                                    <button wire:click="confirmDelete({{ $user->id }})" class="btn btn-sm btn-danger">
                                        <i class="bi-trash"></i>
                                    </button>
                                    @else
                                    <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-primary">
                                        <i class="bi-pencil"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $user->id }})" class="btn btn-sm btn-danger">
                                        <i class="bi-trash"></i>
                                    </button>
                                    <button wire:click="toggleBlock({{ $user->id }})" class="btn btn-sm {{ $user->is_blocked ? 'btn-success' : 'btn-warning' }}">
                                        <i class="{{ $user->is_blocked ? 'bi-unlock' : 'bi-lock' }}"></i>
                                    </button>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    <div class="modal @if($showModal) show @endif" tabindex="-1" role="dialog" style="display: @if($showModal) block @else none @endif;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if($modalType == 'create')
                        Create New User
                        @elseif($modalType == 'edit')
                        Edit User
                        @else
                        View User Details
                        @endif
                    </h5>
                    <button wire:click="$set('showModal', false)" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($modalType == 'view')
                    <div class="d-flex gap-3 align-items-start">
                        <div class="flex-shrink-0" style="width:120px;">
                            @if($photo)
                            <div class="position-relative" style="width:120px;height:120px;">
                                <img src="{{ asset('storage/users/' . $photo) }}" alt="Profile Photo" class="rounded position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                            </div>
                            @else
                            <div class="position-relative" style="width:120px;height:120px;">
                                <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode($name) }}" alt="Avatar" class="rounded position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;">
                            </div>
                            @endif
                        </div>
                        <div class="grow overflow-hidden">
                            <h5 class="mb-1 text-break">{{ $name ?? '-' }}</h5>
                            <div class="mb-1"><strong>Email:</strong> <span class="text-break">{{ $email ?? '-' }}</span></div>
                            <div class="mb-1"><strong>Phone:</strong> <span class="text-break">{{ $phone ?? '-' }}</span></div>
                            <div class="mb-1"><strong>Role:</strong> <span class="text-break">{{ $role ?? '-' }}</span></div>
                            <div class="mb-1"><strong>Address:</strong> <span class="text-break">{{ $address ?? '-' }}</span></div>
                            <div class="mb-1"><strong>Birth Date:</strong> <span class="text-break">{{ $birth_date ? \Carbon\Carbon::parse($birth_date)->format('d M Y') : '-' }}</span></div>
                            <div class="mb-1"><strong>Gender:</strong> <span class="text-break">{{ $gender ? ($gender === 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}</span></div>
                            <div class="mb-1"><strong>Education:</strong> <span class="text-break">{{ $education ?? '-' }}</span></div>
                            <div class="mb-1"><strong>Institution:</strong> <span class="text-break">{{ $institution ?? '-' }}</span></div>
                            <div class="mb-1"><strong>Skills:</strong> <span class="text-break">{{ $skills ?? '-' }}</span></div>
                            <div class="mb-1"><strong>Experience:</strong> <span class="text-break">{{ $experience ?? '-' }}</span></div>
                            <div class="mb-1"><strong>Total Hours:</strong> <span class="text-break">{{ $total_hours ?? '0' }}</span></div>
                            <div class="mb-1"><strong>Created:</strong> <span class="text-break">{{ $created_at ? $created_at->format('d M Y H:i') : '-' }}</span></div>
                            <div class="mb-0"><strong>Last Login:</strong> <span class="text-break">{{ $last_login_at ? $last_login_at->diffForHumans() : 'Never' }}</span></div>
                        </div>
                    </div>
                    @else
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" wire:model="name" @if($modalType=='view' ) disabled @endif>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" wire:model="email" @if($modalType=='view' ) disabled @endif>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @if($modalType != 'view')
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password @if($modalType == 'edit') (Leave blank to keep current) @endif
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" wire:model="password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                id="role" wire:model="role" @if($modalType=='view' ) disabled @endif>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="coordinator">Coordinator</option>
                                <option value="volunteer">Volunteer</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </form>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">
                        Close
                    </button>
                    @if($modalType != 'view')
                    <button type="button" class="btn btn-primary"
                        wire:click="{{ $modalType == 'create' ? 'store' : 'update' }}">
                        {{ $modalType == 'create' ? 'Create' : 'Update' }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal @if($isDelete) show @endif" tabindex="-1" role="dialog" style="display: @if($isDelete) block @else none @endif;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button wire:click="$set('isDelete', false)" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('isDelete', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Backdrop -->
    @if($showModal || $isDelete)
    <div class="modal-backdrop fade show"></div>
    @endif
</div>