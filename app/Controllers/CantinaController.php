<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\pedidoModel;
use App\Models\CardapioModel;


class CantinaController extends BaseController
{
    public function index()
    {
        return view('cantina/inicio');
    }

    public function cardapio()
    {
        $cardapioModel = new CardapioModel();

        $data['comidas'] = $cardapioModel->findAll();

        return view('cantina/cardapio', $data);
    }

    public function cadastros()
    {
        $userModel = new UserModel();
        $data['usuarios'] = $userModel->where('Tipo_usuario', 0)->where('Verificado', 1)->findAll();

        return view('cantina/cadastros', $data);
    }

    public function reservas()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('Pedido');
        $builder->select('*');
        $builder->where('Pago', 1);
        $builder->where('Data', date('Y-m-d'));
        $builder->join('Usuario', 'Usuario.id = Pedido.Usuario_id');
        $query = $builder->get();

        $reservas = $query->getResult();
        $data['reservas'] = $reservas;
        $data['total'] = count($reservas);

        return view('cantina/reservas', $data);
    }

    public function historico()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('Pedido');
        $builder->select('*');
        $builder->where('Pago', 1);

        $dataAtual = time();
        $dataUmaSemanaAtrás = $dataAtual - (7 * 24 * 60 * 60);
        //$dataUmaSemanaAtrás = date('Y-m-d 00:00:00', $dataUmaSemanaAtrás);
        $dataUmaSemanaAtrás = date('Y-m-d', $dataUmaSemanaAtrás);
        
        if($this->request->getGet("date")){
            //dataFiltroStart = date('Y-m-d 00:00:00', strtotime($this->request->getGet("date")));
            //$dataFiltroEnd = date('Y-m-d 23:59:59', strtotime($this->request->getGet("date")));
            //$builder->where('Criado_em >=', $dataFiltroStart);
            //$builder->where('Criado_em <=', $dataFiltroEnd);
            $builder->where('Data', $this->request->getGet("date"));
        }else{
            //$builder->where('Criado_em >= ', $dataUmaSemanaAtrás);
            $builder->where('Data >=', $dataUmaSemanaAtrás);
        }

        $builder->join('Usuario', 'Usuario.id = Pedido.Usuario_id');
        $query = $builder->get();

        $reservas = $query->getResult();
        $data['reservas'] = $reservas;
        $data['total'] = count($reservas);

        return view('cantina/historico', $data);
    }
    
}
