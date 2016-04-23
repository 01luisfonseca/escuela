<?php

namespace App\Helpers\Contracts;

Interface NoOrphanRegistersContract
{

    public function getLimpiarHuerfanos();
    public function eliminarMateriasHasNivelesHuerfanos();
    public function eliminarPeriodosHasNivelesHuerfanos();
    public function eliminarAsistenciasHuerfanos();
    public function eliminarNotasHuerfanos();
    public function eliminarAlumnosHuerfanos();
    public function eliminarEmpleadosHuerfanos();
    public function hayMateriasHasNiveles($id);
    public function hayNivelesHasPeriodos($id);
    public function hayPeriodo($id);
    public function hayMateria($id);
    public function hayNivel($id);
    public function hayUsuario($id);
    public function hayAlumno($id);
    public function hayEmpleado($id);
    public function esUtil($variable);

}