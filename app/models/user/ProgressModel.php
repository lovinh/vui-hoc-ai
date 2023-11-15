<?php

namespace app\core\model\user;

use app\core\model\BaseModel;

use function app\core\helper\load_model;

class ProgressModel extends BaseModel
{
    public function add_progess(int $user_id, int $course_id, int $lesson_id, int $section_id)
    {
        $progress_id = $this->db->table('process')
            ->where('process_user_id', '=', $user_id)
            ->where('process_course_id', '=', $course_id)
            ->where('process_lesson_id', '=', $lesson_id)
            ->where('process_section_id', '=', $section_id)
            ->first();

        if (empty($progress_id))
            return $this->db->table('process')
                ->insert_value([
                    "process_user_id" => $user_id,
                    "process_course_id" => $course_id,
                    "process_lesson_id" => $lesson_id,
                    "process_section_id" => $section_id,
                ]);
        else return false;
    }

    public function get_progress(int $user_id, int $course_id)
    {
        $progress = $this->db->table('process')
            ->where('process_user_id', '=', $user_id)
            ->where('process_course_id', '=', $course_id)
            ->get();

        return $progress;
    }

    public function get_nearest_progress(int $user_id, int $course_id)
    {
        $progress = $this->db->table('process')
            ->where('process_user_id', '=', $user_id)
            ->where('process_course_id', '=', $course_id)
            ->order_by('process_complete_time', true)
            ->first();

        return $progress;
    }
    public function is_complete(int $user_id, int $course_id, int $lesson_id, int $section_id)
    {
        $id = $this->db->table('process')
            ->where('process_user_id', '=', $user_id)
            ->where('process_course_id', '=', $course_id)
            ->where('process_lesson_id', '=', $lesson_id)
            ->where('process_section_id', '=', $section_id)
            ->first();
        if (empty($id))
            return true;
        return false;
    }
    public function is_lesson_complete(int $user_id, int $course_id, int $lesson_id)
    {
        $id = $this->db->table('process')
            ->where('process_user_id', '=', $user_id)
            ->where('process_course_id', '=', $course_id)
            ->where('process_lesson_id', '=', $lesson_id)
            ->get();

        if (empty($id))
            return false;

        /**
         * @var SectionModel
         */
        $section_model = load_model('user\SectionModel');
        $sections = $section_model->get_sections($lesson_id);
        if (empty($sections))
            return false;
        if (count($id) != count($sections))
            return false;
        return true;
    }

    public function get_course_precent_complete(int $user_id, int $course_id)
    {
        /**
         * @var LessonModel
         */
        $lesson_model = load_model('user\LessonModel');
        /**
         * @var SectionModel
         */
        $section_model = load_model('user\SectionModel');
        $course_lessons = $lesson_model->get_lessons($course_id);
        $n_sections = 0;
        if (!empty($course_lessons)) {
            foreach ($course_lessons as $lesson) {
                $sections = $section_model->get_sections($lesson['lesson_id']);
                if (!empty($sections)) {
                    $n_sections += count($sections);
                }
            }
        }

        $n_courses_complete = 0;
        $course_complete = $this->db->table('process')
            ->where('process_user_id', '=', $user_id)
            ->where('process_course_id', '=', $course_id)
            ->get();
        if (!empty($course_complete)) {
            $n_courses_complete = count($course_complete);
        }

        return round(($n_courses_complete / $n_sections) * 100);
    }

    public function get_lesson_precent_complete(int $user_id, int $course_id, int $lesson_id)
    {
        /**
         * @var SectionModel
         */
        $section_model = load_model('user\SectionModel');
        $n_sections = 0;
        $sections = $section_model->get_sections($lesson_id);
        if (!empty($sections)) {
            $n_sections += count($sections);
        }
        $n_courses_complete = 0;
        $course_complete = $this->db->table('process')
            ->where('process_user_id', '=', $user_id)
            ->where('process_course_id', '=', $course_id)
            ->where('process_lesson_id', '=', $lesson_id)
            ->get();
        if (!empty($course_complete)) {
            $n_courses_complete = count($course_complete);
        }

        return round(($n_courses_complete / $n_sections) * 100);
    }
}
