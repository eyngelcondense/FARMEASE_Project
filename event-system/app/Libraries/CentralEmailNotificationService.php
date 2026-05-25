<?php

namespace App\Libraries;

use App\Models\PaymentModel;
use CodeIgniter\Config\Services;

class CentralEmailNotificationService
{
    private PaymentModel $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
    }

    public function sendBookingCreated(int $bookingId): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        if ($snapshot === null) {
            $this->logDispatch('booking_created', 'failed', '', 'booking-not-found', ['booking_id' => $bookingId]);
            return;
        }

        $paymentSummary = $this->getPaymentSummary($bookingId, $snapshot);

        $this->dispatchNotification(
            'booking_created',
            'Booking Request Received - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            [
                'client' => $snapshot['client_email'] ?? '',
                'admin' => $this->getAdminEmails(),
                'studio' => $this->getStudioEmailsByBooking($bookingId),
            ],
            $this->buildEmailPayload(
                'Booking Created',
                'A new booking request has been logged in the system.',
                'Pending',
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Event Date' => $this->formatDate($snapshot['event_date'] ?? ''),
                    'Schedule' => $this->formatSchedule($snapshot),
                    'Package / Venue' => $this->formatPackageVenue($snapshot),
                    'Client' => $snapshot['client_name'] ?? '-',
                ],
                [
                    'Total Amount' => $this->formatCurrency((float) ($snapshot['total_amount'] ?? 0)),
                    'Paid' => $this->formatCurrency((float) $paymentSummary['total_paid']),
                    'Balance' => $this->formatCurrency((float) $paymentSummary['balance']),
                    'Payment Status' => ucfirst((string) ($snapshot['payment_status'] ?? 'pending')),
                ],
                [
                    'Status: ' . ucfirst((string) ($snapshot['status'] ?? 'pending')),
                    'Reference: ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    public function sendBookingApproved(int $bookingId, string $statusLabel = 'Approved'): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        if ($snapshot === null) {
            $this->logDispatch('booking_approved', 'failed', '', 'booking-not-found', ['booking_id' => $bookingId]);
            return;
        }

        $paymentSummary = $this->getPaymentSummary($bookingId, $snapshot);

        $this->dispatchNotification(
            'booking_approved',
            $statusLabel . ' Booking - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            [
                'client' => $snapshot['client_email'] ?? '',
                'studio' => $this->getStudioEmailsByBooking($bookingId),
                'staff' => $this->getAssignedStaffEmailsByBooking($bookingId),
            ],
            $this->buildEmailPayload(
                'Booking ' . $statusLabel,
                'Your booking has been confirmed for scheduling and preparation.',
                $statusLabel,
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Event Date' => $this->formatDate($snapshot['event_date'] ?? ''),
                    'Schedule' => $this->formatSchedule($snapshot),
                    'Assignment' => !empty($snapshot['staff_names']) ? $snapshot['staff_names'] : 'Will be assigned shortly',
                ],
                [
                    'Total Amount' => $this->formatCurrency((float) ($snapshot['total_amount'] ?? 0)),
                    'Paid' => $this->formatCurrency((float) $paymentSummary['total_paid']),
                    'Balance' => $this->formatCurrency((float) $paymentSummary['balance']),
                    'Reminder' => (float) $paymentSummary['balance'] > 0 ? 'Please settle remaining balance before event date.' : 'Fully settled',
                ],
                [
                    'Reference: ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
                    'Please keep this confirmation for your records.',
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    public function sendPaymentReceived(int $bookingId, int $paymentId): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        $payment = $this->getPaymentById($paymentId);

        if ($snapshot === null || $payment === null) {
            $this->logDispatch('payment_received', 'failed', '', 'snapshot-or-payment-missing', [
                'booking_id' => $bookingId,
                'payment_id' => $paymentId,
            ]);
            return;
        }

        $paymentSummary = $this->getPaymentSummary($bookingId, $snapshot);

        $this->dispatchNotification(
            'payment_received',
            'Payment Received - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            [
                'client' => $snapshot['client_email'] ?? '',
                'admin' => $this->getAdminEmails(),
                'studio' => $this->getStudioEmailsByBooking($bookingId),
            ],
            $this->buildEmailPayload(
                'Payment Received',
                'A payment has been successfully recorded for this booking.',
                'Paid',
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Payment Reference' => $payment['payment_reference'] ?? ('PAY-' . $paymentId),
                    'Method' => ucfirst(str_replace('_', ' ', (string) ($payment['payment_method'] ?? 'n/a'))),
                    'Payment Date' => $this->formatDateTime($payment['payment_date'] ?? ''),
                ],
                [
                    'Received Amount' => $this->formatCurrency((float) ($payment['amount'] ?? 0)),
                    'Total Paid' => $this->formatCurrency((float) $paymentSummary['total_paid']),
                    'Remaining Balance' => $this->formatCurrency((float) $paymentSummary['balance']),
                    'Payment Status' => ucfirst((string) ($snapshot['payment_status'] ?? 'pending')),
                ],
                [
                    'Reference: ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    public function sendPaymentFullyPaid(int $bookingId): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        if ($snapshot === null) {
            $this->logDispatch('payment_fully_paid', 'failed', '', 'booking-not-found', ['booking_id' => $bookingId]);
            return;
        }

        $paymentSummary = $this->getPaymentSummary($bookingId, $snapshot);

        $this->dispatchNotification(
            'payment_fully_paid',
            'Booking Fully Paid - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            [
                'client' => $snapshot['client_email'] ?? '',
                'admin' => $this->getAdminEmails(),
                'studio' => $this->getStudioEmailsByBooking($bookingId),
            ],
            $this->buildEmailPayload(
                'Payment Fully Settled',
                'This booking has now been fully paid.',
                'Fully Paid',
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Event Date' => $this->formatDate($snapshot['event_date'] ?? ''),
                    'Package / Venue' => $this->formatPackageVenue($snapshot),
                ],
                [
                    'Total Amount' => $this->formatCurrency((float) ($snapshot['total_amount'] ?? 0)),
                    'Total Paid' => $this->formatCurrency((float) $paymentSummary['total_paid']),
                    'Balance' => $this->formatCurrency((float) $paymentSummary['balance']),
                    'Status' => 'All dues settled',
                ],
                [
                    'Next steps: Prepare for event execution and final coordination.',
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    public function sendBookingCancelled(int $bookingId, string $reason = ''): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        if ($snapshot === null) {
            $this->logDispatch('booking_cancelled', 'failed', '', 'booking-not-found', ['booking_id' => $bookingId]);
            return;
        }

        $refundAmount = (float) ($snapshot['refund_amount'] ?? 0);
        $paymentSummary = $this->getPaymentSummary($bookingId, $snapshot);

        $this->dispatchNotification(
            'booking_cancelled',
            'Booking Cancelled - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            [
                'client' => $snapshot['client_email'] ?? '',
                'admin' => $this->getAdminEmails(),
                'studio' => $this->getStudioEmailsByBooking($bookingId),
                'staff' => $this->getAssignedStaffEmailsByBooking($bookingId),
            ],
            $this->buildEmailPayload(
                'Booking Cancelled',
                'This booking has been cancelled and refund details have been evaluated.',
                'Cancelled',
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Cancellation Reason' => $reason !== '' ? $reason : ((string) ($snapshot['cancellation_reason'] ?? 'N/A')),
                    'Cancellation Policy' => 'Refund amount follows current policy based on event timeline and payment record.',
                ],
                [
                    'Payment Summary' => 'Paid ' . $this->formatCurrency((float) $paymentSummary['total_paid']) . ' of ' . $this->formatCurrency((float) ($snapshot['total_amount'] ?? 0)),
                    'Refund Eligible' => $refundAmount > 0 ? 'Yes' : 'No',
                    'Refund Amount' => $this->formatCurrency($refundAmount),
                    'Refund Status' => ucfirst((string) ($snapshot['refund_status'] ?? 'not_applicable')),
                ],
                [
                    'Reference: ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    public function sendRefundProcessed(int $bookingId, string $notes = ''): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        if ($snapshot === null) {
            $this->logDispatch('refund_processed', 'failed', '', 'booking-not-found', ['booking_id' => $bookingId]);
            return;
        }

        $refundAmount = (float) ($snapshot['refund_amount'] ?? 0);
        $paidAmount = (float) $this->paymentModel->getTotalPaidAmount($bookingId);
        $percentage = $paidAmount > 0 ? round(($refundAmount / $paidAmount) * 100, 2) : 0;

        $this->dispatchNotification(
            'refund_processed',
            'Refund Processed - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            [
                'client' => $snapshot['client_email'] ?? '',
            ],
            $this->buildEmailPayload(
                'Refund Processed',
                'Your refund has been marked as processed.',
                'Refunded',
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Processed At' => $this->formatDateTime((string) ($snapshot['refund_processed_at'] ?? '')),
                    'Reference Number' => (string) ($snapshot['refund_reference_number'] ?? '-'),
                ],
                [
                    'Refund Amount' => $this->formatCurrency($refundAmount),
                    'Refund Percentage' => $percentage . '%',
                    'Original Paid Amount' => $this->formatCurrency($paidAmount),
                    'Processing Notes' => $notes !== '' ? $notes : 'Please keep this message as confirmation of processing.',
                ],
                [
                    'Reference: ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    public function sendBookingCompleted(int $bookingId): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        if ($snapshot === null) {
            $this->logDispatch('booking_completed', 'failed', '', 'booking-not-found', ['booking_id' => $bookingId]);
            return;
        }

        $this->dispatchNotification(
            'booking_completed',
            'Booking Completed - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            [
                'client' => $snapshot['client_email'] ?? '',
                'admin' => $this->getAdminEmails(),
                'studio' => $this->getStudioEmailsByBooking($bookingId),
            ],
            $this->buildEmailPayload(
                'Booking Completed',
                'Thank you for completing your event with Farmease.',
                'Completed',
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Event Date' => $this->formatDate($snapshot['event_date'] ?? ''),
                    'Package / Venue' => $this->formatPackageVenue($snapshot),
                    'Client' => $snapshot['client_name'] ?? '-',
                ],
                [
                    'Total Amount' => $this->formatCurrency((float) ($snapshot['total_amount'] ?? 0)),
                    'Payment Status' => ucfirst((string) ($snapshot['payment_status'] ?? 'pending')),
                    'Review' => 'We appreciate your feedback for continuous service improvement.',
                ],
                [
                    'Reference: ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    public function sendAssignmentNotification(int $bookingId, array $staffIds, string $role, ?string $notes = null): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        if ($snapshot === null) {
            $this->logDispatch('staff_assignment_created', 'failed', '', 'booking-not-found', ['booking_id' => $bookingId]);
            return;
        }

        $staffRecipientMap = $this->getStaffRecipientMap($staffIds);
        if ($staffRecipientMap === []) {
            $this->logDispatch('staff_assignment_created', 'failed', '', 'no-staff-recipient', [
                'booking_reference' => $snapshot['booking_reference'] ?? ('#' . $bookingId),
            ]);
            return;
        }

        $recipientEmails = array_column($staffRecipientMap, 'email');

        $this->dispatchNotification(
            'staff_assignment_created',
            'New Assignment - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            [
                'staff' => $recipientEmails,
            ],
            $this->buildEmailPayload(
                'Staff Assignment Created',
                'You have been assigned to a booking event.',
                'Assigned',
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Client' => $snapshot['client_name'] ?? '-',
                    'Event Date' => $this->formatDate($snapshot['event_date'] ?? ''),
                    'Schedule' => $this->formatSchedule($snapshot),
                    'Role' => ucwords(str_replace('_', ' ', $role)),
                    'Notes' => $notes ?: 'N/A',
                ],
                [
                    'Package / Venue' => $this->formatPackageVenue($snapshot),
                ],
                [
                    'Reference: ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    public function sendBookingExpired(int $bookingId, bool $includeClient = false): void
    {
        $snapshot = $this->getBookingSnapshot($bookingId);
        if ($snapshot === null) {
            $this->logDispatch('booking_expired', 'failed', '', 'booking-not-found', ['booking_id' => $bookingId]);
            return;
        }

        $paymentSummary = $this->getPaymentSummary($bookingId, $snapshot);

        $recipientGroups = [
            'admin' => $this->getAdminEmails(),
        ];

        if ($includeClient) {
            $recipientGroups['client'] = $snapshot['client_email'] ?? '';
        }

        $this->dispatchNotification(
            'booking_expired',
            'Booking Expired - ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
            $recipientGroups,
            $this->buildEmailPayload(
                'Booking Expired',
                'This booking has reached an expired status.',
                'Expired',
                [
                    'Booking Reference' => $snapshot['booking_reference'] ?? '-',
                    'Event Date' => $this->formatDate($snapshot['event_date'] ?? ''),
                    'Current Status' => ucfirst((string) ($snapshot['status'] ?? 'expired')),
                ],
                [
                    'Total Amount' => $this->formatCurrency((float) ($snapshot['total_amount'] ?? 0)),
                    'Paid' => $this->formatCurrency((float) $paymentSummary['total_paid']),
                    'Balance' => $this->formatCurrency((float) $paymentSummary['balance']),
                    'Payment Status' => ucfirst((string) ($snapshot['payment_status'] ?? 'pending')),
                ],
                [
                    'Reference: ' . ($snapshot['booking_reference'] ?? ('#' . $bookingId)),
                ]
            ),
            (string) ($snapshot['booking_reference'] ?? ('#' . $bookingId))
        );
    }

    private function dispatchNotification(string $type, string $subject, array $recipientGroups, array $payload, string $reference): void
    {
        $recipients = $this->normalizeRecipients($recipientGroups);

        if ($recipients === []) {
            $this->logDispatch($type, 'failed', '', 'no-valid-recipients', ['reference' => $reference]);
            return;
        }

        foreach ($recipients as $recipient) {
            try {
                $email = Services::email();
                $email->clear(true);
                $email->setTo($recipient);
                $email->setSubject($subject);
                $email->setMessage(view('emails/notification', $payload));

                if (! $email->send()) {
                    $debug = strip_tags((string) $email->printDebugger(['headers']));
                    $this->logDispatch($type, 'failed', $recipient, 'send-failed', [
                        'reference' => $reference,
                        'debug' => $debug,
                    ]);
                    continue;
                }

                $this->logDispatch($type, 'success', $recipient, 'sent', ['reference' => $reference]);
            } catch (\Throwable $e) {
                $this->logDispatch($type, 'failed', $recipient, 'exception', [
                    'reference' => $reference,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function normalizeRecipients(array $recipientGroups): array
    {
        $emails = [];

        foreach ($recipientGroups as $value) {
            if (is_array($value)) {
                foreach ($value as $email) {
                    $emails[] = (string) $email;
                }
                continue;
            }

            if (is_string($value)) {
                $emails[] = $value;
            }
        }

        $emails = array_unique(array_filter(array_map(static fn ($email) => trim((string) $email), $emails)));

        return array_values(array_filter($emails, static fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false));
    }

    private function getBookingSnapshot(int $bookingId): ?array
    {
        $db = db_connect();

        $result = $db->table('bookings b')
            ->select('b.*, c.fullname as client_name, c.email as client_email, p.name as package_name, v.name as venue_name')
            ->join('clients c', 'c.id = b.client_id', 'left')
            ->join('packages p', 'p.id = b.package_id', 'left')
            ->join('venues v', 'v.id = b.venue_id', 'left')
            ->where('b.id', $bookingId)
            ->get()
            ->getRowArray();

        if ($result === null) {
            return null;
        }

        $result['staff_names'] = $this->getAssignedStaffNamesByBooking($bookingId);

        return $result;
    }

    private function getPaymentSummary(int $bookingId, array $snapshot): array
    {
        $totalPaid = (float) $this->paymentModel->getTotalPaidAmount($bookingId);
        $totalAmount = (float) ($snapshot['total_amount'] ?? 0);

        return [
            'total_paid' => $totalPaid,
            'balance' => max($totalAmount - $totalPaid, 0),
        ];
    }

    private function getPaymentById(int $paymentId): ?array
    {
        return $this->paymentModel->find($paymentId);
    }

    private function getAdminEmails(): array
    {
        $db = db_connect();

        $rows = $db->table('auth_groups_users agu')
            ->select('u.username as email')
            ->join('users u', 'u.id = agu.user_id', 'left')
            ->where('agu.group', 'admin')
            ->get()
            ->getResultArray();

        return array_values(array_filter(array_map(static function (array $row): string {
            return trim((string) ($row['email'] ?? ''));
        }, $rows)));
    }

    private function getStudioEmailsByBooking(int $bookingId): array
    {
        $db = db_connect();

        $rows = $db->table('studio_bookings sb')
            ->select('u.username as email')
            ->join('studios s', 's.id = sb.studio_id', 'left')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->where('sb.booking_id', $bookingId)
            ->get()
            ->getResultArray();

        return array_values(array_filter(array_map(static function (array $row): string {
            return trim((string) ($row['email'] ?? ''));
        }, $rows)));
    }

    private function getAssignedStaffNamesByBooking(int $bookingId): string
    {
        $db = db_connect();

        if (! $db->tableExists('staff_assignments')) {
            return '';
        }

        $rows = $db->table('staff_assignments sa')
            ->select('s.name')
            ->join('staffs s', 's.id = sa.staff_id', 'left')
            ->where('sa.booking_id', $bookingId)
            ->get()
            ->getResultArray();

        $names = array_values(array_filter(array_map(static fn (array $row): string => trim((string) ($row['name'] ?? '')), $rows)));

        return implode(', ', $names);
    }

    private function getAssignedStaffEmailsByBooking(int $bookingId): array
    {
        $db = db_connect();

        if (! $db->tableExists('staff_assignments')) {
            return [];
        }

        $rows = $db->table('staff_assignments sa')
            ->select('COALESCE(NULLIF(s.email, ""), u.username) as email')
            ->join('staffs s', 's.id = sa.staff_id', 'left')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->where('sa.booking_id', $bookingId)
            ->get()
            ->getResultArray();

        return array_values(array_filter(array_map(static function (array $row): string {
            return trim((string) ($row['email'] ?? ''));
        }, $rows)));
    }

    private function getStaffRecipientMap(array $staffIds): array
    {
        $ids = array_values(array_filter(array_map('intval', $staffIds), static fn ($id) => $id > 0));
        if ($ids === []) {
            return [];
        }

        $db = db_connect();
        $rows = $db->table('staffs s')
            ->select('s.id, s.name, COALESCE(NULLIF(s.email, ""), u.username) as email')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->whereIn('s.id', $ids)
            ->get()
            ->getResultArray();

        return array_values(array_filter(array_map(static function (array $row): array {
            return [
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'email' => trim((string) ($row['email'] ?? '')),
            ];
        }, $rows), static fn (array $row) => $row['email'] !== ''));
    }

    private function buildEmailPayload(
        string $title,
        string $intro,
        string $status,
        array $details,
        array $summary,
        array $notes = []
    ): array {
        return [
            'title' => $title,
            'intro' => $intro,
            'status' => $status,
            'details' => $details,
            'summary' => $summary,
            'notes' => $notes,
            'footerText' => 'Farmease Notifications - This is an automated message from the central event system.',
        ];
    }

    private function formatCurrency(float $amount): string
    {
        return 'PHP ' . number_format($amount, 2);
    }

    private function formatDate(string $date): string
    {
        if ($date === '') {
            return '-';
        }

        $timestamp = strtotime($date);
        return $timestamp ? date('M d, Y', $timestamp) : $date;
    }

    private function formatDateTime(string $value): string
    {
        if ($value === '') {
            return '-';
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('M d, Y h:i A', $timestamp) : $value;
    }

    private function formatSchedule(array $snapshot): string
    {
        $start = $snapshot['start_time'] ?? '';
        $end = $snapshot['end_time'] ?? '';

        if ($start === '' && $end === '') {
            return '-';
        }

        $startText = $start !== '' ? date('h:i A', strtotime((string) $start)) : '-';
        $endText = $end !== '' ? date('h:i A', strtotime((string) $end)) : '-';

        return $startText . ' to ' . $endText;
    }

    private function formatPackageVenue(array $snapshot): string
    {
        $package = trim((string) ($snapshot['package_name'] ?? 'N/A'));
        $venue = trim((string) ($snapshot['venue_name'] ?? 'N/A'));

        return $package . ' / ' . $venue;
    }

    private function logDispatch(string $type, string $status, string $recipient, string $detail, array $context = []): void
    {
        $payload = [
            'type' => $type,
            'status' => $status,
            'recipient' => $recipient,
            'detail' => $detail,
            'context' => $context,
        ];

        $level = $status === 'success' ? 'info' : 'error';
        log_message($level, 'CentralEmailNotificationService: ' . json_encode($payload));
    }
}
