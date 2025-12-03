<?php

namespace Tests\Feature;

use App\Models\ClassType;
use App\Models\ScheduledClass;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\ClassTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InstructorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_instructor_is_redirected_to_instructor_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirectToRoute('instructor.dashboard');

        $this->followRedirects($response)->assertSee('Hey Instructor');
    }


    public function test_instructor_can_schedule_a_course(): void
    {
        // Given
        $user = User::factory()->create([
            'role' => 'instructor',
        ]);

        $this->seed(ClassTypeSeeder::class);

        // When
        $response = $this->actingAs($user)->post('/instructor/schedule', [
            'class_type_id' => ClassType::first()->id,
            'date' => '2026-01-01',
            'time' => '10:00:00',
        ]);

        // Then
        $this->assertDatabaseHas('scheduled_classes', [
            'class_type_id' => ClassType::first()->id,
            'date_time' => '2026-01-01 10:00:00',
        ]);

        $response->assertRedirectToRoute('schedule.index');
    }


    public function test_instructor_can_cancel_course(): void
    {
        $user = User::factory()->create([
            'role' => 'instructor',
        ]);

        $this->seed(ClassTypeSeeder::class);

        $scheduledCourse = ScheduledClass::create([
            'instructor_id' => $user->id,
            'class_type_id' => ClassType::first()->id,
            'date_time' => '2026-01-01 10:00:00',
        ]);

        $response = $this->actingAs($user)->delete(route('schedule.destroy', $scheduledCourse));

        $this->assertDatabaseMissing('scheduled_classes', [
            'id' => $scheduledCourse->id,
        ]);
    }


//    public function test_cannot_cancel_course_less_than_two_hours_before(): void
//    {
//        $user = User::factory()->create([
//            'role' => 'instructor',
//        ]);
//
//        $this->seed(ClassTypeSeeder::class);
//
//        $scheduledCourse = ScheduledClass::create([
//            'instructor_id' => $user->id,
//            'class_type_id' => ClassType::first()->id,
//            'date_time' => now()->addHours(1)->minutes(0)->seconds(0),
//        ]);
//
//        $response = $this->actingAs($user)->get(route('schedule.index'));
//
//        $response->assertDontSee('Cancel');
//
//        $response = $this->actingAs($user)->delete(route('schedule.destroy', $scheduledCourse));
//    }
}
