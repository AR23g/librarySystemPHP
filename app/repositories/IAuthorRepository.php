<?php
namespace App\repositories;

/**
 * Interface IAuthorRepository - Contrato específico para operaciones de autores
 */

interface IAuthorRepository extends Repository {
    /**
     * Verifica si un autor tiene libros asociados
     * @param int $authorId ID del autor
     * @return bool Verdadero si tiene libros
     */
    public function hasBooks($authorId);
}
?>
