<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\CustomValidationException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;
use App\Exceptions\UnauthorizedException;
use Illuminate\Http\Request;

/**
 * Example controller demonstrating how to use custom exceptions.
 *
 * This controller is for demonstration purposes only and should not be used in production.
 */
class ExampleExceptionController extends Controller
{
    /**
     * Show examples of different exceptions.
     */
    public function index()
    {
        return view('example.exceptions', [
            'exceptionTypes' => [
                'BadRequestException',
                'CustomValidationException',
                'ForbiddenException',
                'NotFoundException',
                'ServerErrorException',
                'UnauthorizedException',
            ]
        ]);
    }

    /**
     * Demonstrate throwing a BadRequestException.
     */
    public function badRequest()
    {
        throw new BadRequestException(
            'This is an example of a bad request exception',
            400,
            null,
            ['param' => 'invalid_value']
        );
    }

    /**
     * Demonstrate throwing a CustomValidationException.
     */
    public function validationError()
    {
        throw new CustomValidationException(
            'This is an example of a validation exception',
            [
                'name' => ['The name field is required'],
                'email' => ['The email must be a valid email address'],
            ],
            422
        );
    }

    /**
     * Demonstrate throwing a ForbiddenException.
     */
    public function forbidden()
    {
        throw new ForbiddenException(
            'This is an example of a forbidden exception',
            403,
            null,
            ['resource' => 'example_resource']
        );
    }

    /**
     * Demonstrate throwing a NotFoundException.
     */
    public function notFound()
    {
        throw new NotFoundException(
            'This is an example of a not found exception',
            404,
            null,
            ['resource_id' => 123]
        );
    }

    /**
     * Demonstrate throwing a ServerErrorException.
     */
    public function serverError()
    {
        throw new ServerErrorException(
            'This is an example of a server error exception',
            500,
            null,
            ['server' => 'app_server_1']
        );
    }

    /**
     * Demonstrate throwing an UnauthorizedException.
     */
    public function unauthorized()
    {
        throw new UnauthorizedException(
            'This is an example of an unauthorized exception',
            401,
            null,
            ['required_role' => 'admin']
        );
    }

    /**
     * Example of how to use try/catch with custom exceptions.
     */
    public function tryCatchExample(Request $request)
    {
        try {
            // Simulate some operation that might fail
            $userId = $request->input('user_id');

            if (empty($userId)) {
                throw new BadRequestException('User ID is required');
            }

            // Simulate a database lookup
            $user = null; // In a real app, this would be User::find($userId)

            if (!$user) {
                throw new NotFoundException("User with ID {$userId} not found");
            }

            // Simulate an authorization check
            $isAdmin = false; // In a real app, this would check user roles

            if (!$isAdmin) {
                throw new ForbiddenException('Only administrators can access this resource');
            }

            // If we get here, everything is successful
            return response()->json(['success' => true, 'message' => 'Operation completed successfully']);

        } catch (BadRequestException $e) {
            // Handle bad request specifically if needed
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => $e->getErrorCode(),
            ], 400);

        } catch (NotFoundException | ForbiddenException $e) {
            // Group similar exceptions together
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => $e->getErrorCode(),
            ], $e instanceof NotFoundException ? 404 : 403);

        } catch (\Exception $e) {
            // Catch any other exceptions
            // In a real app, you might want to log this error
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error_code' => 'UNEXPECTED_ERROR',
            ], 500);
        }
    }
}
