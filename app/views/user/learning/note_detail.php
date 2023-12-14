@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;
use function app\core\helper\str_datetime;

$model = $data['model'];

$note = $model['note'];

@endphp

<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2>Note Detail</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->
<!--================ Start Course Details Area =================-->
<section class="course_details_area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="note-detail-content">
                    <div class="card">
                        <h5 class="card-header">
                            <i class="fas fa-calendar-week"></i>
                            <span>Create on {{ str_datetime($note['note_created_time']) }}</span>
                        </h5>
                        <div class="card-body">
                            <div class="card-text">
                                {! $note['note_content'] !}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 right-contents">
                <a href="{{ route_url('user.learning.note', ['id' => $model['course_id']]) }}" class="genric-btn primary-border p-1 text-uppercase enroll rounded-0">Back to notes list</a>
                <a href="{{ route_url('user.learning.edit_note', ['id' => $model['course_id'], 'note_id' => $note['note_id']]) }}" class="genric-btn info-border p-1 text-uppercase enroll rounded-0">Edit note</a>
                <button type="button" class="genric-btn danger p-1 text-uppercase enroll rounded-0 text-white" data-toggle="modal" data-target="#deleteNote">Delete note</button>
                <div class="modal fade" id="deleteNote" tabindex="-1" role="dialog" aria-labelledby="#deleteNoteLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteNoteLabel">Confirm deleting note</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure to delete this note. Remember, this note will be deleted permanently!
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="genric-btn info-border" data-dismiss="modal">Close</button>
                                <form action="{{ route_url('user.learning.deleting_note', ['id' => $model['course_id'], 'note_id' => $note['note_id']]) }}" method="post">
                                    <button type="submit" class="genric-btn danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Course Details Area =================-->