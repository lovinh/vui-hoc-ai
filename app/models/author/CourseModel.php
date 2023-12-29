<?php

namespace app\core\model\author;

use app\core\model\BaseModel;

class CourseModel extends BaseModel
{
    private $data = [];
    public function __construct()
    {
        parent::__construct();
    }
    public function get_total_courses(string $user_id)
    {
        $data = $this->db->table('course')
            ->select_field("COUNT(course_id)")
            ->where('course_author_id', '=', $user_id)
            ->first();
        return $data['COUNT(course_id)'] ?? null;
    }
    public function get_courses(string $user_id)
    {
        return $this->db->table('course')
            ->select_field()
            ->where('course_author_id', '=', $user_id)
            ->get();
    }

    public function get_courses_overview(string $user_id)
    {
        $data = $this->db->table('course')
            ->join('subject', 'subject.subject_id = course.course_subject_id')
            ->select_field('course.course_id, course.course_name, course.course_publish_date, course.course_status, course.course_price, subject.subject_name')
            ->where('course_author_id', '=', $user_id)
            ->get();
        return $data;
    }
    public function get_available_courses_overview(string $user_id)
    {
        $data = $this->db->table('course')
            ->join('subject', 'subject.subject_id = course.course_subject_id')
            ->select_field('course.course_id, course.course_name, course.course_publish_date, course.course_status, course.course_price, subject.subject_name')
            ->where('course_author_id', '=', $user_id)
            ->where('course_status', '=', 'available')
            ->get();
        return $data;
    }
    public function get_draft_courses_overview(string $user_id)
    {
        $data = $this->db->table('course')
            ->join('subject', 'subject.subject_id = course.course_subject_id')
            ->select_field('course.course_id, course.course_name, course.course_publish_date, course.course_status, course.course_price, subject.subject_name')
            ->where('course_author_id', '=', $user_id)
            ->where('course_status', '=', 'draft')
            ->get();
        return $data;
    }

    public function get_course_overview(string $course_id, string $user_id)
    {
        $data = $this->db->table('course')
            ->join("subject", "subject.subject_id = course.course_subject_id")
            ->select_field()
            ->where('course_author_id', '=', $user_id)
            ->where('course_id', '=', $course_id)
            ->first();
        return $data;
    }

    public function get_course_subject(string $course_id, string $user_id)
    {
        $data = $this->db->table('course')
            ->join("subject", "subject.subject_id = course.course_subject_id")
            ->select_field("subject.subject_name")
            ->where('course_author_id', '=', $user_id)
            ->where('course_id', '=', $course_id)
            ->first();
        return $data["subject_name"] ?? null;
    }

    public function is_author(string $course_id, string $user_id)
    {
        $data = $this->db->table('course')
            ->select_field()
            ->where('course_author_id', '=', $user_id)
            ->where('course_id', '=', $course_id)
            ->first();
        if (empty($data))
            return false;
        return true;
    }

    public function get_last_course_id()
    {
        $id = $this->db->table('course')->select_field('course_id')->order_by('course_id', true)->first();
        if (empty($id)) return null;
        return $id['course_id'];
    }

    public function get_course_name(string $course_id, string $user_id)
    {
        $name = $this->db->table('course')
            ->select_field('course_name')
            ->where('course_id', '=', $course_id)
            ->where('course_author_id', '=', $user_id)
            ->first();
        return $name['course_name'] ?? null;
    }

    public function insert(string $user_id, string $course_name, string $course_subject_id, string $course_description = null, string $course_thumbnail = null, string $course_banner = null, int $course_price = 0)
    {
        $last_course = $this->db->table('course')
            ->select_field('course_id')->order_by('course_id', true)->first();

        $course_id = intval($last_course["course_id"]) + 1;

        $a = $this->db->table('course')->insert_value([
            "course_name" => $course_name,
            "course_description" => $course_description,
            "course_subject_id" => $course_subject_id,
            "course_status" => "draft",
            "course_thumbnail" => $course_thumbnail,
            "course_banner" => $course_banner,
            "course_price" => $course_price,
            "course_author_id" => $user_id
        ]);
        $b = $this->db->table('course_update')->insert_value([
            "course_id" => $course_id,
            "course_update_description" => "<p>Course Created!</p>"
        ]);

        return $a && $b;
    }

    public function update(string $user_id, string $course_id, string $course_name, string $course_subject_id, string $course_description = null, string $course_thumbnail = null, string $course_banner = null, int $course_price = 0)
    {
        $check = $this->db->table('course')
            ->where('course_id', '=', $course_id)
            ->where('course_author_id', '=', $user_id)
            ->update_value([
                "course_name" => $course_name,
                "course_subject_id" => $course_subject_id,
                "course_description" => $course_description,
                "course_price" => $course_price,
            ]);
        if ($course_thumbnail != null) {
            $check = $check && $this->db->table('course')
                ->where('course_id', '=', $course_id)
                ->update_value([
                    "course_thumbnail" => $course_thumbnail,
                ]);
        }
        if ($course_banner != null) {
            $check = $check && $this->db->table('course')
                ->where('course_id', '=', $course_id)
                ->update_value([
                    "course_banner" => $course_banner,
                ]);
        }
        return $check;
    }

    public function update_status(string $user_id, string $course_id, string $course_status = "draft")
    {
        if ($course_status != "draft" && $course_status != "available" && $course_status != "Not available")
            return false;
        return $this->db->table('course')
            ->where('course_id', '=', $course_id)
            ->where('course.course_author_id', '=', $user_id)
            ->update_value([
                "course_status" => $course_status,
            ]);
    }

    public function delete(string $user_id, string $course_id)
    {
        return $this->db->table('course')
            ->where('course_id', '=', $course_id)
            ->where('course_author_id', '=', $user_id)
            ->delete_value();
    }

    public function can_active(string $user_id, string $course_id)
    {
        $course = $this->db->table('course')
            ->where('course_id', '=', $course_id)
            ->where('course_author_id', '=', $user_id)
            ->first();
        if (empty($course)) {
            var_dump("false vì course rỗng");
            return false;
        }
        if (empty($course['course_description'])) {
            var_dump("false vì course description rỗng");
            return false;
        }
        if (empty($course['course_subject_id'])) {
            var_dump("false vì course subject id rỗng");
            return false;
        }
        $lessons = $this->db->table('course')
            ->join('lesson', 'lesson.lesson_course_id = course.course_id')
            ->where('course.course_id', '=', $course_id)
            ->select_field('course.course_id, lesson.lesson_id, lesson.lesson_description')
            ->get();
        if (empty($lessons)) {
            var_dump("false vì lessons rỗng");
            return false;
        }
        foreach ($lessons as $lesson) {
            if (empty($lesson['lesson_description'])) {
                var_dump("false vì lesson description rỗng");
                return false;
            }
            $sections = $this->db->table('lesson')
                ->join('section', 'lesson.lesson_id = section.lesson_id')
                ->where('lesson.lesson_id', '=', $lesson['lesson_id'])
                ->select_field()
                ->get();
            if (empty($sections)) {
                var_dump("false vì lesson section rỗng");
                return false;
            }
            foreach ($sections as $section) {
                if (empty($section['section_content'])) {
                    var_dump("false vì section content rỗng");
                    return false;
                }
            }
        }
        return true;
    }
}
