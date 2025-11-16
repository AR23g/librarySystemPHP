<?php
namespace App\repositories;

/**
 * Contrato base para repositorios de datos
 * Define operaciones CRUD estándar que todo repositorio debe implementar
 * Garantiza consistencia entre diferentes tipos de repositorios
 */
interface Repository {
    /**
     * Recupera todos los registros de la entidad
     * @return array Lista completa de elementos
     */
    public function getAll();

    /**
     * Busca un elemento específico por su identificador único
     * @param int $id Identificador del elemento a buscar
     * @return object|null Elemento encontrado o null si no existe
     */
    public function getById($id);

    /**
     * Crea un nuevo elemento en la base de datos
     * @param array $data Array asociativo con los datos del nuevo elemento
     * @return object Elemento creado incluyendo el ID asignado
     */
    public function create($data);

    /**
     * Actualiza los datos de un elemento existente
     * @param int $id Identificador del elemento a modificar
     * @param array $data Array con los campos a actualizar
     * @return bool true si la actualización fue exitosa, false en caso contrario
     */
    public function update($id, $data);

    /**
     * Elimina permanentemente un elemento de la base de datos
     * @param int $id Identificador del elemento a eliminar
     * @return bool true si la eliminación fue exitosa, false en caso contrario
     */
    public function delete($id);
}
?>
