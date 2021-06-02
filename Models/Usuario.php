<?php 

    class Usuario {
        private $_id;
        private $_nombre_usuario;
        private $_foto;

        public function __construct($id, $nombre_usuario, $foto) {
            $this->setId($id);
            $this->setNombreUsuario($nombre_usuario);
            $this->setFoto($foto);
        }

        public function getId() {
            return $this->_id;
        }

        public function getNombreUsuario() {
            return $this->_nombre_usuario;
        }

        public function getFoto() {
            return $this->_foto;
        }

        public function setId($id) {
            $this->_id = $id;
        }

        public function setNombreUsuario($nombre_usuario) {
            $this->_nombre_usuario = $nombre_usuario;
        }

        public function setFoto($foto) {
            $this->_foto = $foto;
        }

        public function returnArray() {
            $usuario = array();

            $usuario["id"] = $this->getId();
            $usuario["nombre_usuario"] = $this->getNombreUsuario();
            $usuario["foto"] = $this->getFoto();

            return $usuario;
        }
    }

?>