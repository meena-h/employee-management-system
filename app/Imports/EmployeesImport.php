<?php

namespace App\Imports;

use App\Models\Employee;
use App\Services\EmployeeCodeGenerator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EmployeesImport extends DefaultValueBinder implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithCustomValueBinder
{
    use SkipsFailures;

    protected int $importedCount = 0;

    public function __construct(protected int $createdBy) {}

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && in_array($cell->getColumn(), ['C', 'L'])) {
            $value = ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function model(array $row)
    {
        if (empty(array_filter($row))) {
        return null;
    }

        $exists = Employee::where('personal_email', $row['personal_email'] ?? null)
            ->orWhere('phone_number', $row['phone_number'] ?? null)
            ->exists();

        if ($exists) {
            return null;
        }

        $this->importedCount++;

        return new Employee([
            'employee_code' => app(EmployeeCodeGenerator::class)->generate(),
            'created_by' => $this->createdBy,
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'date_of_birth' => $row['date_of_birth'],
            'gender' => $row['gender'],
            'marital_status' => $row['marital_status'],
            'personal_email' => $row['personal_email'] ?? null,
            'phone_number' => $row['phone_number'],
            'current_address' => $row['current_address'],
            'permanent_address' => $row['permanent_address'],
            'department' => $row['department'] ?? null,
            'designation' => $row['designation'],
            'date_of_joining' => $row['date_of_joining'],
            'employment_status' => $row['employment_status'] ?? 'active',
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'required_with:last_name', 'string', 'max:100'],
            'last_name' => ['nullable', 'required_with:first_name', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'required_with:first_name', 'date'],
            'gender' => ['nullable', 'required_with:first_name', 'in:male,female,other'],
            'marital_status' => ['nullable', 'required_with:first_name', 'in:single,married,divorced,widowed'],
            'personal_email' => ['nullable', 'email'],
            'phone_number' => ['nullable', 'required_with:first_name', 'string'],
            'current_address' => ['nullable', 'required_with:first_name', 'string'],
            'permanent_address' => ['nullable', 'required_with:first_name', 'string'],
            'designation' => ['nullable', 'required_with:first_name', 'string', 'max:100'],
            'date_of_joining' => ['nullable', 'required_with:first_name', 'date'],
        ];
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }
}