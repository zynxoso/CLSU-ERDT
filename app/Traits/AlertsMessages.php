<?php

namespace App\Traits;

trait AlertsMessages
{
    /**
     * Flash a success message to the session.
     *
     * @param string $message
     * @return $this
     */
    protected function success($message)
    {
        session()->flash('success', $message);
        return $this;
    }

    /**
     * Flash an error message to the session.
     *
     * @param string $message
     * @return $this
     */
    protected function error($message)
    {
        session()->flash('error', $message);
        return $this;
    }

    /**
     * Flash a warning message to the session.
     *
     * @param string $message
     * @return $this
     */
    protected function warning($message)
    {
        session()->flash('warning', $message);
        return $this;
    }

    /**
     * Flash an info message to the session.
     *
     * @param string $message
     * @return $this
     */
    protected function info($message)
    {
        session()->flash('info', $message);
        return $this;
    }
}
