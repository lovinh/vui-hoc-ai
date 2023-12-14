@php
use function app\core\helper\assets;
use function app\core\helper\public_url;
use function app\core\helper\route_url;
use function app\core\helper\str_datetime;
$model = $data['model'];


@endphp

<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="banner_content text-center">
                        <h2>Course Note</h2>
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
                @if (!empty($model['msg']))
                @if ($model['msg']['type'] == "create_note")
                @if ($model['msg']['status'])
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Create new note successfully!
                </div>
                @else
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Create new note fail! Please try again!
                </div>
                @endif
                @elif ($model['msg']['type'] == "delete_note")
                @if ($model['msg']['status'])
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Delete note successfully!
                </div>
                @else
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Delete note fail! Please try again!
                </div>
                @endif
                @else
                @if ($model['msg']['status'])
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Edit note successfully!
                </div>
                @else
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Edit note fail! Please try again!
                </div>
                @endif
                @endif
                @endif
                <h2>Your current notes:</h2>
                <div class="note-content">
                    @if (empty($model['notes']))
                    <div class="text-center">No note yet!</div>
                    @else
                    @foreach ($model['notes'] as $note)
                    <div class="card note-item">
                        <h5 class="card-header">
                            <i class="fas fa-calendar-week"></i>
                            <span>Create on {{ str_datetime($note['note_created_time']) }}</span>
                        </h5>
                        <div class="card-body">
                            <div class="card-text note-item-body">
                                {! $note['note_content'] !}
                            </div>
                            <div class="note-item-nav">
                                <a href="{{ route_url('user.learning.note_detail', ['id' => $model['course_id'], 'note_id' => $note['note_id']]) }}" class="genric-btn info-border radius">Continue reading</a>
                                <button class="genric-btn danger radius" type="submit" data-toggle="modal" data-target="#exampleModal{{ $note['note_id'] }}">Delete note</button>
                            </div>
                            <div class="modal fade" id="exampleModal{{ $note['note_id'] }}" tabindex="-1" role="dialog" aria-labelledby="#exampleModalLabel{{ $note['note_id'] }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="#exampleModalLabel{{ $note['note_id'] }}">Confirm deleting note</h5>
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
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="col-lg-4 right-contents">
                <ul>
                    <h3>Don't want to forget? Take a note now!</h3>
                    <!-- <br> -->
                    <a href="{{ route_url('user.learning.create_note', ['id' => $model['course_id']]) }}" class="genric-btn info p-1 text-uppercase enroll rounded-0 text-white">Create new note</a>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--================ End Course Details Area =================-->
