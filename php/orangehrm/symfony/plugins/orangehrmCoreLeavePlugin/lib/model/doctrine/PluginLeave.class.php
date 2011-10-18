<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginLeave extends BaseLeave {
    const LEAVE_STATUS_LEAVE_REJECTED = -1;
    const LEAVE_STATUS_LEAVE_CANCELLED = 0;
    const LEAVE_STATUS_LEAVE_PENDING_APPROVAL = 1;
    const LEAVE_STATUS_LEAVE_APPROVED = 2;
    const LEAVE_STATUS_LEAVE_TAKEN = 3;
    const LEAVE_STATUS_LEAVE_WEEKEND = 4;
    const LEAVE_STATUS_LEAVE_HOLIDAY = 5;

    private $leaveStatusText = array(
        self::LEAVE_STATUS_LEAVE_REJECTED => 'Rejected',
        self::LEAVE_STATUS_LEAVE_CANCELLED => 'Canceled',
        self::LEAVE_STATUS_LEAVE_PENDING_APPROVAL => 'Pending Approval',
        self::LEAVE_STATUS_LEAVE_APPROVED => 'Scheduled',
        self::LEAVE_STATUS_LEAVE_TAKEN => 'Taken',
    );
    private $nonWorkingDayStatuses = array(
        self::LEAVE_STATUS_LEAVE_WEEKEND,
        self::LEAVE_STATUS_LEAVE_HOLIDAY,
    );

    public function getTextLeaveStatus() {
        if (array_key_exists($this->getLeaveStatus(), $this->leaveStatusText)) {
            $status = $this->getLeaveStatus();
            return $this->leaveStatusText[$status];
        }

        return '';
    }

    public function getStatusTextList() {
        return $this->leaveStatusText;
    }

    public function canApprove() {
        if (!$this->getLeaveRequest()->getLeaveType()->getAvailableFlag()) {
            return false;
        }
        $canApprove = ($this->getLeaveStatus() == self::LEAVE_STATUS_LEAVE_PENDING_APPROVAL);
        $canApprove |= ( $this->getLeaveStatus() == self::LEAVE_STATUS_LEAVE_HOLIDAY);
        return $canApprove;
    }

    public function canCancel($isAdmin = false) {

        $canCancel = ($this->getLeaveStatus() == self::LEAVE_STATUS_LEAVE_PENDING_APPROVAL);
        $canCancel |= ( $this->getLeaveStatus() == self::LEAVE_STATUS_LEAVE_HOLIDAY);
        $canCancel |= ( $this->getLeaveStatus() == self::LEAVE_STATUS_LEAVE_WEEKEND);
        $canCancel |= ( $this->getLeaveStatus() == self::LEAVE_STATUS_LEAVE_APPROVED);
        $canCancel |= ( $this->getLeaveStatus() == self::LEAVE_STATUS_LEAVE_TAKEN && $isAdmin);

        return $canCancel;
    }

    public function isNonWorkingDay() {
        if (($this->getLeaveLengthHours() == 0.00) && in_array($this->getLeaveStatus(), $this->nonWorkingDayStatuses)) {
            return true;
        }
        //return in_array($this->getLeaveStatus(), $this->nonWorkingDayStatuses);
    }

    public function getLeavePeriodId() {
        return $this->getLeaveRequest()->getLeavePeriodId();
    }

    public function getNumberOfDays() {
        return $this->getLeaveRequest()->getNumberOfDays();
    }

    public function getDetailedLeaveListQuotaHolderValue() {
        return "1";
    }

    public function getDetailedLeaveListRequestIdHolderValue() {
        return "0";
    }

    public function getLeaveDurationAsAString() {

        if ($this->getStartTime() != '00:00:00' || $this->getEndTime() != '00:00:00') {
            return "(" . (date("H:i", strtotime($this->getStartTime()))) . " - " . date("H:i", strtotime($this->getEndTime())) . ")";
        } else {
            return '';
        }
    }

    public function getFormattedLeaveDateToView() {
        return set_datepicker_date_format($this->getLeaveDate());
    }

}