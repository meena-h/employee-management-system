<?php

namespace App\Services;

use App\Models\Employee;
use FPDF;

class BiodataPdfService
{
    public function generate(Employee $employee): string
    {
        $employee->load(['familyMembers', 'educations', 'experiences']);

        $pdf = new FPDF();
        $pdf->AddPage();

        // ===== Title =====
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Employee Biodata', 0, 1, 'C');
        $pdf->Ln(4);

        // ===== Personal Information =====
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 8, 'Personal Information', 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 11);

        $this->row($pdf, 'Employee Code', $employee->employee_code);
        $this->row($pdf, 'Name', $employee->first_name . ' ' . $employee->last_name);
        $this->row($pdf, 'Date of Birth', $employee->date_of_birth);
        $this->row($pdf, 'Gender', ucfirst($employee->gender));
        $this->row($pdf, 'Marital Status', ucfirst($employee->marital_status));
        $this->row($pdf, 'Phone', $employee->phone_number);
        $this->row($pdf, 'Email', $employee->personal_email ?? '-');
        $this->row($pdf, 'Department', $employee->department ?? '-');
        $this->row($pdf, 'Designation', $employee->designation);
        $this->row($pdf, 'Date of Joining', $employee->date_of_joining);
        $this->row($pdf, 'Employment Status', ucfirst($employee->employment_status));
        $this->row($pdf, 'Current Address', $employee->current_address);
        $this->row($pdf, 'Permanent Address', $employee->permanent_address);
        $pdf->Ln(4);

        // ===== Family Members =====
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Family Members', 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 11);

        if ($employee->familyMembers->isEmpty()) {
            $pdf->Cell(0, 7, 'No family members recorded.', 0, 1);
        } else {
            foreach ($employee->familyMembers as $member) {
                $pdf->Cell(0, 7, "{$member->name} - " . ucfirst($member->relationship), 0, 1);
            }
        }
        $pdf->Ln(4);

        // ===== Education =====
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Education', 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 11);

        if ($employee->educations->isEmpty()) {
            $pdf->Cell(0, 7, 'No education records.', 0, 1);
        } else {
            foreach ($employee->educations as $edu) {
                $score = $edu->score_type === 'cgpa'
                    ? "{$edu->score_value} CGPA"
                    : "{$edu->score_value}%";
                $pdf->Cell(0, 7, "{$edu->degree} - {$edu->institution} ({$edu->year_of_passing}) - {$score}", 0, 1);
            }
        }
        $pdf->Ln(4);

        // ===== Experience =====
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Experience', 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 11);

        if ($employee->experiences->isEmpty()) {
            $pdf->Cell(0, 7, 'No experience records.', 0, 1);
        } else {
            foreach ($employee->experiences as $exp) {
                $end = $exp->end_date ?? 'Present';
                $pdf->Cell(0, 7, "{$exp->designation} at {$exp->company_name} ({$exp->start_date} - {$end})", 0, 1);
                $pdf->Cell(0, 6, "Total: {$exp->total_experience_months} months", 0, 1);
            }
        }

        return $pdf->Output('S'); // 'S' = return as string, not send directly to browser
    }

    private function row(FPDF $pdf, string $label, ?string $value): void
    {
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(50, 7, $label . ':', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, $value ?? '-', 0, 1);
    }
}