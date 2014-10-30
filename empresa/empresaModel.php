<?php

class empresaModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    function delete($arg = false) {
        if ($arg) {
            $count = $this->_db->exec("DELETE FROM empresa where Nit=" . $arg);
            return $count;
        } else {
            return 0;
        }
    }

    function edit($arg = false) {
        if ($_POST && $arg) {
            $count = $this->_db->exec("UPDATE empresa SET Nombre='" . $_POST['txtNombre'] . "', NombreRepLeg='" . $_POST['txtNombreRepLeg'] . "', Departamento='" . $_POST['txtDepartamento'] . "' WHERE Nit=" . $arg);
            return $count;
        } else {
            return 0;
        }
    }

    function get($arg = false) {
        $sql = $this->_db->query("SELECT Nit,Nombre,NombreRepLeg,Departamento,SectorEconomico FROM empresa WHERE Nit=" . $arg);
        return $sql->fetchall();
    }

    function set() {
        if ($_POST) {
            $count = $this->_db->exec("INSERT INTO empresa (Nit,Nombre,NombreRepLeg,Departamento,SectorEconomico) VALUES ('" . $_POST['txtNit'] . "', '" . $_POST['txtNombre'] . "', '" . $_POST['txtNombreRepLeg'] . "', '" . $_POST['cmbDepartamento'] . "', '" . $_POST['cmbSectorEconomico'] . "')");
            return $count;
        } else {
            return 0;
        }
    }

    function lista() {
        $data = $this->_db->query("SELECT Nit,Nombre,TipoEmpresa,Departamento,SectorEconomico FROM empresa");
        return $data->fetchall();
    }

    public function buscardocumento() {
        $sql = $this->_db->query("SELECT Nit FROM empresa where Nit LIKE '%" . $_POST['txtNit'] . "%'");
        return $sql->fetchall();
    }

    public function intencion() {
        $sql = $this->_db->query("SELECT Nit, Nombre, SectorEconomico FROM empresa WHERE TipoEmpresa='Comprador'");
        $compra = $sql->fetchall();
        for ($i = 0; $i < count($compra); $i++) {
            $sql2 = $this->_db->query("SELECT categorias.Id, categorias.Nombre FROM empresa 
            INNER JOIN intencioncompra ON empresa.Nit=intencioncompra.Empresa
            INNER JOIN categorias ON intencioncompra.Categoria=categorias.Id
            WHERE empresa.Nit='" . $compra[$i]['Nit'] . "'");
            $data = $sql2->fetchall();
            $intencion[$compra[$i]['Nit']] = $data;
        }
        return $intencion;
    }

    public function productos() {
        $sql = $this->_db->query("SELECT Nit, Nombre, SectorEconomico FROM empresa WHERE TipoEmpresa='Vendedor'");
        $vende = $sql->fetchall();
        for ($i = 0; $i < count($vende); $i++) {
            $sql2 = $this->_db->query("SELECT categorias.Id, categorias.Nombre AS NomCat, productos.Nombre AS NomPro  FROM empresa 
            INNER JOIN productos ON empresa.Nit=productos.Empresa
            INNER JOIN categorias ON productos.Categoria=categorias.Id
            WHERE empresa.Nit='" . $vende[$i]['Nit'] . "'");
            $data = $sql2->fetchall();
            $productos[$vende[$i]['Nit']] = $data;
        }
        return $productos;
    }

    public function numvende() {
        $sql = $this->_db->query("SELECT Nit, Nombre, SectorEconomico FROM empresa WHERE TipoEmpresa='Vendedor' ");
        $num = $sql->fetchall();
        return $num;
    }

    public function numcompra() {
        $sql = $this->_db->query("SELECT Nit, Nombre, SectorEconomico FROM empresa WHERE TipoEmpresa='Comprador' ");
        $num = $sql->fetchall();
        return $num;
    }

    public function vende($arg = '') {
        if ($arg != '') {
            $arg="  AND productos.Categoria=".$arg; 
        }        $sql = $this->_db->query("SELECT DISTINCT empresa.Nit, empresa.Nombre, empresa.SectorEconomico FROM empresa
INNER JOIN productos ON empresa.Nit=productos.Empresa
WHERE TipoEmpresa='Vendedor' " . $arg);
        $num = $sql->fetchall();
        return $num;
    }

    public function compra($arg = '') {
        if ($arg != '') {
            $arg="  AND intencioncompra.Categoria=".$arg; 
        }
        $sql = $this->_db->query("SELECT empresa.Nit, empresa.Nombre, empresa.SectorEconomico FROM empresa 
INNER JOIN intencioncompra ON empresa.Nit=intencioncompra.Empresa
WHERE TipoEmpresa='Comprador'" . $arg);
        $num = $sql->fetchall();
        return $num;
    }

}

?>
