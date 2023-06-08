<?php
namespace Admin\Auth;

use Admin\Auth\Session;

class LoginAttempts extends Session
{
    private $maxFailedAttempts = 3;
    private $username;
    private $lockoutDuration = 1800; // 30 minutes
    private $status = [];

    /**
     * LoginAttempts constructor.
     *
     * @param string $username The username to track login attempts for.
     * @param int $maxFailedAttempts The maximum number of failed attempts allowed.
     */
    public function __construct($username, $maxFailedAttempts = 3)
    {
        $this->maxFailedAttempts = $maxFailedAttempts;
        $this->username = $username;
    }

    /**
     * Validates a login attempt for the given username.
     *
     * @param string $username The username to validate the login attempt for.
     * @return bool Returns true if the login attempt is valid, false otherwise.
     */
    public function validateLoginAttempt($username)
    {
        if ($this->isAccountLocked($username)) {
            $this->status[] = "Your account is locked. Try again after " . $this->getRemainTime($username) . " minutes.";
            return false;
        }

        if ($this->isMaxFailedAttemptsExceeded($username)) {
            $this->setLockoutTime($username, time());
            $this->status[] = "You exceeded the login attempts limit. Your account is locked. Try again after " . $this->getRemainTime($username) . " minutes.";
            return false;
        }

        return true;
    }

    /**
     * Gets the status messages from the login attempts.
     *
     * @return array The status messages.
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Increments the number of failed login attempts for the given username.
     *
     * @param string $username The username to increment the failed login attempts for.
     */
    public function incrementFailedLoginAttempts($username)
    {
        $failedAttempts = $this->getFailedLoginAttempts($username);
        $failedAttempts++;
        $remainAttempts = $this->maxFailedAttempts - $failedAttempts;
        $this->setFailedLoginAttempts($this->username, $failedAttempts);
        $this->setRemainAttempts($this->username, $remainAttempts);
    }

    /**
     * Sets the number of failed login attempts for the given username.
     *
     * @param string $username The username to set the failed login attempts for.
     * @param int $attempts The number of failed login attempts.
     */
    private function setFailedLoginAttempts($username, $attempts)
    {
        self::set('failed_attempts_' . $username, $attempts);
    }

    /**
     * Gets the number of failed login attempts for the given username.
     *
     * @param string $username The username to get the failed login attempts for.
     * @return int The number of failed login attempts.
     */
    private function getFailedLoginAttempts($username)
    {
        return self::get('failed_attempts_' . $username, 0);
    }

    /**
     * Resets the failed login attempts and lockout time for the given username.
     *
     * @param string $username The username to reset the failed login attempts for.
     */
    public function resetFailedLoginAttempts($username)
    {
        $this->setFailedLoginAttempts($username, 0);
        $this->setLockoutTime($username);
    }

    /**
     * Checks if the maximum number of failed attempts has been exceeded for the given username.
     *
     * @param string $username The username to check the failed attempts for.
     * @return bool Returns true if the maximum failed attempts have been exceeded, false otherwise.
     */
    private function isMaxFailedAttemptsExceeded($username)
    {
        $failedAttempts = $this->getFailedLoginAttempts($username);
        return $failedAttempts >= $this->maxFailedAttempts;
    }

    /**
     * Gets the remaining login attempts for the given username.
     *
     * @param string $username The username to get the remaining login attempts for.
     * @return int The remaining login attempts.
     */
    public function getRemainAttempts($username)
    {
        return self::get('remaining_attempts_' . $username, 0);
    }

    /**
     * Sets the remaining login attempts for the given username.
     *
     * @param string $username The username to set the remaining login attempts for.
     */
    public function setRemainAttempts($username)
    {
        $attempts = $this->maxFailedAttempts - $this->getFailedLoginAttempts($username);
        self::set('remaining_attempts_' . $username, $attempts);
    }

    /**
     * Sets the lockout time for the given username.
     *
     * @param string $username The username to set the lockout time for.
     * @param int|false $time The lockout time (in seconds) or false to clear the lockout time.
     */
    private function setLockoutTime($username, $time = false)
    {
        self::set('lockout_time_' . $username, $time);
    }

    /**
     * Gets the lockout time for the given username.
     *
     * @param string $username The username to get the lockout time for.
     * @return int|false The lockout time (in seconds) or false if not set.
     */
    private function getLockoutTime($username)
    {
        return self::get('lockout_time_' . $username);
    }

    /**
     * Gets the remaining time until the account lockout is lifted for the given username.
     *
     * @param string $username The username to get the remaining time for.
     * @return int The remaining time in minutes.
     */
    private function getRemainTime($username)
    {
        $lockoutTime = $this->getLockoutTime($username);
        $time = $this->lockoutDuration + $lockoutTime - time();
        return round($time / 60);
    }

    /**
     * Checks if the account is locked for the given username.
     *
     * @param string $username The username to check if the account is locked for.
     * @return bool Returns true if the account is locked, false otherwise.
     */
    private function isAccountLocked($username)
    {
        if ($this->isMaxFailedAttemptsExceeded($username)) {
            $lockoutTime = $this->getLockoutTime($username);
            if ($lockoutTime !== false && ($lockoutTime + $this->lockoutDuration) > time()) {
                return true;
            }
        }

        return false;
    }
}
