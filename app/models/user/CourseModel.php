<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

class CourseModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function is_course_exists(int $course_id)
    {
        return $this->db->table('course')->select_field('course_id')->where('course_id', '=',  $course_id)->first() == null ? false : true;
    }
    public function get_courses()
    {
        $list_id = $this->db->table('course')->select_field('course_id')->get();
        $courses = [];
        if (!empty($list_id))
            foreach ($list_id as $key => $course) {
                $courses[] = [
                    "id" => $course['course_id'],
                    "name" => $this->get_course_name($course['course_id']),
                    "subject" => $this->get_course_subject($course['course_id']),
                    "price" => $this->get_course_price($course['course_id']),
                    "author" => $this->get_course_author_id($course['course_id']),
                    "thumbnail" => $this->get_course_thumbnail($course['course_id'])
                ];
            }
        return $courses;
    }

    public function get_course_subject(int $course_id)
    {
        $subject = $this->db->table('course')->join('subject', 'course.course_subject_id = subject.subject_id')->select_field('subject_name')->where('course.course_id', '= ', $course_id)->first();

        if (!empty($subject)) {
            return $subject['subject_name'];
        }
        return null;
    }

    public function get_course_author(int $course_id)
    {
        $author = $this->db->table('course')->join('users', 'course.course_author_id = users.user_id')->select_field('users.user_first_name, users.user_last_name')->where('course.course_id', '=', $course_id)->first();

        if (!empty($author)) {
            return $author['user_first_name'] . " " . $author['user_last_name'];
        }
        return null;
    }

    public function get_course_author_avatar(int $course_id)
    {
        $author = $this->db->table('course')->join('users', 'course.course_author_id = users.user_id')->select_field('user_avatar_link')->where('course.course_id', '=', $course_id)->first();

        if (!empty($author)) {
            return $author['user_avatar_link'];
        }
        return null;
    }

    public function get_course_author_id(int $course_id)
    {
        $author = $this->db->table('course')->join('users', 'course.course_author_id = users.user_id')->select_field('users.user_id')->where('course.course_id', '=', $course_id)->first();

        if (!empty($author)) {
            return $author["user_id"];
        }
        return null;
    }

    /**
     * Trả về khóa học theo tên chủ đề
     */
    public function get_courses_by_subject(string $subject)
    {
        $list_id = $this->db->table('course')->join('subject', 'course.course_subject_id = subject.subject_id')->select_field('course.course_id')->where('subject.subject_name', '=', $subject)->get();
        $courses = [];
        if (!empty($list_id))
            foreach ($list_id as $key => $course) {
                $courses[] = [
                    "id" => $course['course_id'],
                    "name" => $this->get_course_name($course['course_id']),
                    "subject" => $this->get_course_subject($course['course_id']),
                    "price" => $this->get_course_price($course['course_id']),
                    "author" => $this->get_course_author_id($course['course_id']),
                    "thumbnail" => $this->get_course_thumbnail($course['course_id'])
                ];
            }
        return $courses;
    }

    /**
     * Trả về danh sách bài học khóa học gồm id và tên
     */
    public function get_course_lessons(int $course_id)
    {
        $lesson = $this->db->table('course')
            ->join('lesson', 'lesson.lesson_course_id = course.course_id')
            ->select_field('lesson.lesson_id, lesson.lesson_title')
            ->where('course.course_id', '=', $course_id)
            ->get();

        return $lesson;
    }

    /**
     * Trả về ngày update gần nhất của khóa học
     */
    public function get_course_last_update_time(int $course_id)
    {
        $last_update = $this->db->table('course')
            ->join('course_update', 'course_update.course_id = course.course_id')
            ->select_field("course_update_time")
            ->where('course.course_id', '=', $course_id)
            ->order_by('course_update_id', true)
            ->first();

        if (!empty($last_update)) {
            return date("d-m-Y", strtotime($last_update["course_update_time"]));
        }
        return null;
    }

    public function get_course_publish_date(int $course_id)
    {
        $course_publish_date = $this->db->table('course')->select_field('course_publish_date')->where('course_id', '=', $course_id)->first();

        if (!empty($course_publish_date)) {
            return date("d-m-Y", strtotime($course_publish_date["course_publish_date"]));;
        }
        return null;
    }

    /**
     * Trả về tên khóa học
     */
    public function get_course_name(int $course_id)
    {
        $title = $this->db->table('course')->select_field('course_name')->where('course_id', '=', $course_id)->first();

        if (!empty($title)) {
            return $title['course_name'];
        }
        return null;
    }

    /**
     * Trả về mô tả khóa học
     */
    public function get_course_description(int $course_id)
    {
        $description = $this->db->table('course')->select_field('course_description')->where('course_id', '=', $course_id)->first();

        if (!empty($description)) {
            return $description['course_description'];
        }
        return null;
    }

    /**
     * Trả về phí khóa học
     */
    public function get_course_price(int $course_id)
    {
        $price = $this->db->table('course')->select_field('course_price')->where('course_id', '=', $course_id)->first();

        if (!empty($price)) {
            return $price['course_price'];
        }
        return null;
    }

    /**
     * Trả về review khóa học
     */
    public function get_course_review(int $course_id)
    {
        $reviews = $this->db->table('course')
            ->join('review', 'review.review_course_id = course.course_id')
            ->join('users', 'users.user_id = review.review_user_id')
            ->select_field('users.user_first_name, users.user_last_name, review.review_rate, review.review_time, review.review_content')
            ->where('course.course_id', '=', $course_id)
            ->get();

        if (empty($reviews)) {
            return null;
        }

        $review_returned = [];
        foreach ($reviews as $key => $value) {
            $review_returned[$key] = [
                "user" => $value['user_first_name'] . " " . $value['user_last_name'],
                "time" => date("d-m-Y", strtotime($value["review_time"])),
                "rate" => $value["review_rate"],
                "content" => $value["review_content"]
            ];
        }

        return $review_returned;
    }

    public function get_course_reviews_total_rate(int $course_id)
    {
        $reviews = $this->get_course_review($course_id);
        if ($reviews == null) {
            return 5;
        }

        $total = count($reviews);
        $rate = 5;
        foreach ($reviews as $key => $value) {
            $rate += $value['rate'];
        }

        return $rate / $total;
    }

    public function get_course_banner_uri(int $course_id)
    {
        $banner = $this->db->table('course')->select_field('course_banner')->where('course_id', '=', $course_id)->first();

        if (!empty($banner)) {
            return $banner['course_banner'];
        }
        return null;
    }

    public function get_course_status(int $course_id)
    {
        $status = $this->db->table('course')->select_field('course_status')->where('course_id', '=', $course_id)->first();

        if (!empty($status)) {
            return $status['course_status'];
        }
        return null;
    }

    public function get_course_thumbnail(int $course_id)
    {
        $thumbnail = $this->db->table('course')->select_field('course_thumbnail')->where('course_id', '=', $course_id)->first();

        if (!empty($thumbnail)) {
            return $thumbnail['course_thumbnail'];
        }
        return null;
    }

    public function is_have_course(int $course_id)
    {
        $test = $this->db->table('course')->select_field('course_id')->where('course_id', '=', $course_id)->first();

        if (!empty($test)) {
            return false;
        }
        return true;
    }

    public function get_newest_courses()
    {
        $list_id = $this->db->table('course')->select_field('course_id')->order_by('course_publish_date', true)->limit(3)->get();
        $courses = [];
        foreach ($list_id as $key => $course) {
            $courses[] = [
                "id" => $course['course_id'],
                "name" => $this->get_course_name($course['course_id']),
                "subject" => $this->get_course_subject($course['course_id']),
                "price" => $this->get_course_price($course['course_id']),
                "author" => $this->get_course_author_id($course['course_id']),
                "thumbnail" => $this->get_course_thumbnail($course['course_id'])
            ];
        }

        return $courses;
    }

    public function get_courses_by_search(string $name)
    {
        $list_id = $this->db->table('course')->select_field('course_id')->where_like('course_name', '%' . $name . '%')->get();
        $list_id_subject = $this->db->table('course')
            ->join('subject', 'subject.subject_id = course.course_subject_id')
            ->select_field('course.course_id')
            ->where_like('subject.subject_name', '%' . $name . '%')->get();
        $list_id_author = $this->db->table('course')
            ->join('users', 'course.course_author_id = users.user_id')
            ->select_field('course.course_id')
            ->where_like("users.user_first_name", '%' . $name . '%')
            ->or_where_like("users.user_last_name", '%' . $name . '%')
            ->get();
        // var_dump($list_id_author);
        $final_list_id = [];
        if (!empty($list_id))
            foreach ($list_id as $key => $value) {
                if (!in_array($value, $final_list_id)) {
                    $final_list_id[] = $value;
                }
            }
        if (!empty($list_id_subject))
            foreach ($list_id_subject as $key => $value) {
                if (!in_array($value, $final_list_id)) {
                    $final_list_id[] = $value;
                }
            }
        if (!empty($list_id_author))
            foreach ($list_id_author as $key => $value) {
                if (!in_array($value, $final_list_id)) {
                    $final_list_id[] = $value;
                }
            }
        $courses = [];
        if (!empty($final_list_id))
            foreach ($final_list_id as $key => $course) {
                $courses[] = [
                    "id" => $course['course_id'],
                    "name" => $this->get_course_name($course['course_id']),
                    "subject" => $this->get_course_subject($course['course_id']),
                    "price" => $this->get_course_price($course['course_id']),
                    "author" => $this->get_course_author_id($course['course_id']),
                    "thumbnail" => $this->get_course_thumbnail($course['course_id'])
                ];
            }
        return $courses;
    }
}
