@extends('layouts.dashboard')

@section('title', 'Quản Lý Đánh Giá')
@section('content')

   <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('review-table')
        </div>
    </main>



    <!-- Modal Xóa -->
    <!-- <div id="deleteReviewModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-confirm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="material-icons modal-icon">priority_high</i>
                    <h4 class="modal-title w-100">Xác nhận xóa</h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa đánh giá này không?</p>
                    <p id="modalEntityName" style="font-weight: bold; color: #555;"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
                </div>
            </div>
        </div>
    </div> -->
@endsection
@section('scripts')
<script>
    // $(document).ready(function() {
    //     // Khởi tạo biến lưu trữ URL xóa và dòng cần xóa
    //     let deleteUrl = "";
    //     let rowToDelete = null;

    //     // Khi mở modal xác nhận xóa
    //     $(document).on('show.bs.modal', '#deleteReviewModal', function(event) {
    //         // Đảm bảo chỉ có một modal backdrop
    //         if ($('.modal-backdrop').length > 1) {
    //             $('.modal-backdrop').not(':first').remove();
    //         }
    //         const button = $(event.relatedTarget);
    //         deleteUrl = button.data('url');
    //         rowToDelete = button.closest('tr');
    //         const entityName = button.data('name') || 'đánh giá';
    //         $('#modalEntityName').text(entityName);
            
    //         console.log('Delete URL:', deleteUrl);
    //     });

    //     // Hàm đóng modal đúng cách
    //     function closeModal() {
    //         $('body').removeClass('modal-open');
    //         $('.modal-backdrop').remove();
    //         $('#deleteReviewModal').modal('hide').remove();
    //     }

    //     // Khi click nút xác nhận xóa
    //     $(document).on('click', '#confirmDeleteBtn', function() {
    //         const $btn = $(this);
            
    //         if (!deleteUrl) {
    //             alert('Không tìm thấy URL để xóa!');
    //             return;
    //         }

    //         // Vô hiệu hóa nút và hiển thị trạng thái đang xử lý
    //         $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xóa...').prop('disabled', true);

    //         // Gửi yêu cầu xóa
    //         $.ajax({
    //             url: deleteUrl,
    //             type: 'POST',
    //             data: {
    //                 _token: '{{ csrf_token() }}',
    //                 _method: 'DELETE'
    //             },
    //             dataType: 'json',
    //             success: function(response) {
    //                 console.log('Delete success:', response);
                    
    //                 if (response.success) {
    //                     // Đóng modal
    //                     $('body').removeClass('modal-open');
    //                     $('.modal-backdrop').remove();
    //                     $('#deleteReviewModal').modal('hide');
                        
    //                     // Hiển thị thông báo thành công
    //                     showAlert('success', response.message || 'Đã xóa đánh giá thành công!');
                        
    //                     // Tự động tải lại trang sau 1 giây
    //                     setTimeout(function() {
    //                         window.location.reload();
    //                     }, 500);
    //                 } else {
    //                     showAlert('danger', response.message || 'Không thể xóa đánh giá.');
    //                 }
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error('Delete error:', status, error);
    //                 console.error('Response:', xhr.responseText);
                    
    //                 let errorMessage = 'Đã xảy ra lỗi khi xóa đánh giá.';
                    
    //                 try {
    //                     const response = JSON.parse(xhr.responseText);
    //                     if (response && response.message) {
    //                         errorMessage = response.message;
    //                     }
    //                 } catch (e) {
    //                     console.error('Error parsing error response:', e);
    //                 }
                    
    //                 showAlert('danger', errorMessage);
    //             },
    //             complete: function() {
    //                 // Khôi phục trạng thái nút
    //                 $btn.html('Xóa').prop('disabled', false);
                    
    //                 // Đảm bảo modal được đóng đúng cách nếu có lỗi
    //                 if ($('#deleteReviewModal').length) {
    //                     closeModal();
    //                 }
    //             }
    //         });
    //     });
        
    //     // Hàm hiển thị thông báo
    //     function showAlert(type, message) {
    //         const alertHtml = `
    //             <div class="alert alert-${type} alert-dismissible fade show" role="alert">
    //                 ${message}
    //                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    //                     <span aria-hidden="true">&times;</span>
    //                 </button>
    //             </div>`;
            
    //         // Thêm thông báo vào đầu container
    //         $('.container-xl').prepend(alertHtml);
            
    //         // Tự động ẩn thông báo sau 5 giây
    //         setTimeout(() => {
    //             $('.alert').fadeOut(400, function() {
    //                 $(this).remove();
    //             });
    //         }, 5000);
    //     }
    // });
</script>
@endsection