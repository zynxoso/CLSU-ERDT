<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginContentController extends Controller
{
    /**
     * Get dynamic content for the login page
     * 
     * @return array
     */
    public static function getLoginPageContent()
    {
        return [
            'title' => 'ERDT Program',
            'description' => 'Engineering Research and Development for Technology (ERDT) Program',
            'tabs' => [
                'history' => [
                    'title' => 'Our History',
                    'content' => [
                        'heading' => 'Our History',
                        'paragraphs' => [
                            'The Engineering Research and Development for Technology (ERDT) program was established to enhance the country\'s global competitiveness in the fields of science and technology through research and development.',
                            'CLSU has been a proud participant in this program, contributing to the advancement of engineering education and research in the Philippines.'
                        ]
                    ]
                ],
                'about' => [
                    'title' => 'About ERDT',
                    'content' => [
                        'heading' => 'About ERDT',
                        'paragraphs' => [
                            'The ERDT program is a consortium of premier universities in the Philippines offering graduate programs in various engineering fields. The program aims to:'
                        ],
                        'list_items' => [
                            'Strengthen the country\'s human resource in science and technology',
                            'Enhance research and development capabilities in engineering',
                            'Promote collaboration between industry and academe',
                            'Develop globally competitive engineers and researchers'
                        ],
                        'list_type' => 'unordered'
                    ]
                ],
                'apply' => [
                    'title' => 'How to Apply',
                    'content' => [
                        'heading' => 'How to Apply',
                        'paragraphs' => [
                            'To apply for the ERDT scholarship program, please follow these steps:'
                        ],
                        'list_items' => [
                            'Check the eligibility requirements on the official ERDT website',
                            'Prepare all necessary documents (transcripts, recommendation letters, etc.)',
                            'Submit your application through the CLSU Graduate School',
                            'Pass the entrance examination and interview',
                            'Complete the admission requirements for your chosen graduate program'
                        ],
                        'list_type' => 'ordered',
                        'footer' => 'For more information, please contact the CLSU Graduate School or visit the ERDT office.'
                    ]
                ]
            ],
            'contact' => [
                'text' => 'Need help? Contact us at',
                'email' => 'erdt@clsu.edu.ph'
            ]
        ];
    }
}
