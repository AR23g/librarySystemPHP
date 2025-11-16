<?php
namespace App\repositories;

/**
 * Interface IBookRepository - Contrato específico para operaciones de libros
 * Extiende Repository y añade métodos específicos de libros
 */

interface IBookRepository extends Repository {
    /**
     * Obtiene todos los libros con información de autores
     * @return array Lista de libros con autores
     */
    public function getAllWithAuthors();

    /**
     * Actualiza las copias disponibles de un libro
     * @param int $bookId ID del libro
     * @param int $change Cambio en copias (positivo o negativo)
     * @return bool Verdadero si se actualizó correctamente
     */
    public function updateAvailableCopies($bookId, $change);
}
?>
