<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailTest extends TestCase
{

    public function testMustEnterEmailJson()
    {
        $postData = [
            "emails" => ''
        ];

        $this->json('POST', 'api/send?api_token='.env('API_TOKEN'), $postData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors',
                'data' => [
                    'emails' => [
                        'The emails field is required.'
                    ]
                ]
            ]);
    }

    public function testMustEnterEmailSubjectBodyAttachment()
    {
        $faker = \Faker\Factory::create();

        $emailArr = [
            [
                'email' => '',
                'subject' => '',
                'body' => '',
                'attachment' => [
                    'file_name' => '',
                    'base64_image' => ''
                ]
            ]
        ];

        $postData = [
            "emails" => $emailArr
        ];

        $this->json('POST', 'api/send?api_token='.env('API_TOKEN'), $postData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors',
                'data' => [
                    'emails.0.email' => [
                        'The email field is required.'
                    ],
                    'emails.0.subject' => [
                        'The subject field is required.'
                    ],
                    'emails.0.body' => [
                        'The body field is required.'
                    ],
                    'emails.0.attachment' => [
                        'The attachment must be base64 encoded.',
                        'The attachment file name must be required.'
                    ]
                ]
            ]);
    }

    /**
     * Test the email task.
     *
     * @return void
     */
    public function testEmail()
    {
        $faker = \Faker\Factory::create();

        $emailArr = [
            [
                'email' => 'dhaval.s.bhavsar@gmail.com',
                'subject' => $faker->unique()->sentence,
                'body' => $faker->paragraph,
                'attachment' => [
                    'file_name' => 'sample.jpeg',
                    'base64_image' => base64_encode(storage_path('sample_file/file_example_JPG_100kB.jpeg'))
                ]
            ],
            [
                'email' => 'dhaval.s.bhavsar@gmail.com',
                'subject' => $faker->unique()->sentence,
                'body' => $faker->paragraph,
                'attachment' => [
                    [
                        'file_name' => 'sample123.jpeg',
                        'base64_image' => base64_encode(storage_path('sample_file/file_example_JPG_100kB.jpeg'))
                    ],
                    [
                        'file_name' => 'sample756.jpeg',
                        'base64_image' => base64_encode(storage_path('sample_file/file_example_JPG_100kB.jpeg'))
                    ]
                ]
            ],
            [
                'email' => 'dhaval.s.bhavsar@gmail.com',
                'subject' => $faker->unique()->sentence,
                'body' => $faker->paragraph
            ]
        ];

        $postData = [
            "emails" => $emailArr
        ];

        $this->json('POST', 'api/send?api_token='.env('API_TOKEN'), $postData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Email send successfully.'
            ]);
    }
}
