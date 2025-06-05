<?php

namespace App\Imports;

use App\Models\Participante;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipanteImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    private $idCurso;

    public function __construct($curso)
    {
        $this->idCurso = $curso;
    }

    public function model(array $row)
    {
        $existe = Participante::where('dni', $row['dni_del_participante'])->where('id_curso', $this->idCurso)->first();
        if ($existe) {
            return null;
        }

        $row["participo_como"] = strtolower($row["participo_como"]);


        return new Participante([
            "id_curso" => $this->idCurso,
            "dni" => $row["dni_del_participante"],
            "nombre" => strtoupper($row["nombres_del_participante"]),
            "apellido" => strtoupper($row["apellidos_del_participante"]),
            "correo" => $row["correo_del_participante"],
            "codigo" => $row["codigo"],
            "participo_como" => $row["participo_como"],
            "programa_id"=> $row["programa_id"],
            "tipo_id" => $row["tipo_id"]
        ]);
    }

    public function batchSize(): int
    {
        return 4000;
    }

    public function chunkSize(): int
    {
        return 4000;
    }
}
