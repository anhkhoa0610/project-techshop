@extends('layouts.dashboard')

@section('content')
    <!-- Main Content -->
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('user-table')
        </div>
    </main>
<!-- Modal Xóa -->
    <div id="deleteUserModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-confirm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="material-icons modal-icon">priority_high</i>
                    <h4 class="modal-title w-100">Xác nhận xóa</h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa người dùng này không?</p>
                    <p id="modalEntityName" style="font-weight: bold; color: #555;"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    
</script>
@endsection