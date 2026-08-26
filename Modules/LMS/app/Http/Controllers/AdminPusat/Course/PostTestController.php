<?php

namespace Modules\LMS\Http\Controllers\AdminPusat\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Services\PostTestService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseSection;

class PostTestController extends Controller
{
    public function __construct(private PostTestService $postTestService) {}

    public function create(Request $request)
    {
        $courseSlug = $request->query('course_slug');
        $sectionId = $request->query('section_id');

        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $section = $sectionId ? CourseSection::findOrFail($sectionId) : null;

        return view('lms::admin-pusat.post-test.create', compact('course', 'section'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_slug'                 => 'required|string',
            'course_section_id'           => 'nullable|uuid',
            'course_id'                   => 'nullable|uuid',
            'title'                       => 'required|string|max:255',
            'passing_score'               => 'required|integer|min:0|max:100',
            'duration'                    => 'required|integer|min:1',
            'description'                 => 'nullable|string',
            'questions'                   => 'required|array|min:1',
            'questions.*.question'        => 'required|string',
            'questions.*.choices'         => 'required|array|min:2',
            'questions.*.correct_choice'  => 'required',
        ]);

        $result = $this->postTestService->storePostTestWithQuestions($validated);

        if (!$result['success']) {
            ToastMagic::error($result['message']);
            return redirect()->back()->withInput();
        }

        ToastMagic::success($result['message']);
        return redirect()->route('admin-pusat.management-course.courses.show', $validated['course_slug']);
    }

   public function edit(Request $request, $id)
    {
        $courseSlug = $request->query('course_slug');
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        
        $postTest = \Modules\LMS\Models\PostTest::with('questions.choices')->findOrFail($id);
        
        // Ambil data section jika post test ini adalah post test per bagian
        $section = $postTest->course_section_id ? CourseSection::findOrFail($postTest->course_section_id) : null;

        $formattedQuestions = $postTest->questions->map(function($q) {
            $correctIndex = 0;
            $choices = $q->choices->values()->map(function($c, $index) use (&$correctIndex) {
                if ($c->is_correct) {
                    $correctIndex = $index;
                }
                return $c->choice;
            });
            return [
                'question' => $q->question,
                'choices' => $choices,
                'correct_choice' => $correctIndex
            ];
        });

        return view('lms::admin-pusat.post-test.edit', compact('course', 'section', 'postTest', 'formattedQuestions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_slug'                 => 'required|string',
            'title'                       => 'required|string|max:255',
            'passing_score'               => 'required|integer|min:0|max:100',
            'duration'                    => 'required|integer|min:1',
            'description'                 => 'nullable|string',
            'questions'                   => 'required|array|min:1',
            'questions.*.question'        => 'required|string',
            'questions.*.choices'         => 'required|array|min:2',
            'questions.*.correct_choice'  => 'required',
        ]);

        $postTest = \Modules\LMS\Models\PostTest::findOrFail($id);

        // Panggil service untuk mengupdate post test beserta soalnya
        $result = $this->postTestService->updatePostTestWithQuestions($postTest, $validated);

        if (!$result['success']) {
            ToastMagic::error($result['message']);
            return redirect()->back()->withInput();
        }

        ToastMagic::success($result['message']);
        return redirect()->route('admin-pusat.management-course.courses.show', $validated['course_slug']);
    }
}
