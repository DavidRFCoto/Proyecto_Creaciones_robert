<?php

namespace App\Services;

class CalculadoraTallasService
{
    public function calcularTallaCamisa($h) {
        $tab = [
            'T.4'=>['h'=>30,'l'=>44],'T.6'=>['h'=>32,'l'=>48],'T.8'=>['h'=>34,'l'=>52],
            'T.10'=>['h'=>36,'l'=>56],'T.12'=>['h'=>38,'l'=>60],'T.14'=>['h'=>40,'l'=>64],
            'S'=>['h'=>42,'l'=>68],'M'=>['h'=>44,'l'=>72],'L'=>['h'=>46,'l'=>74],'XL'=>['h'=>48,'l'=>76]
        ];
        foreach($tab as $t => $d) { if($h <= $d['h']) return ['talla'=>$t, 'sug'=>"L:".$d['l']]; }
        return ['talla'=>'Especial', 'sug'=>''];
    }

    public function calcularTallaPantalon($c) {
        $tab = [
            '22'=>['min'=>56,'max'=>57,'cad'=>'67-69','l'=>81,'rod'=>16.5,'rue'=>14.0],
            '24'=>['min'=>60,'max'=>62,'cad'=>'73-75','l'=>85,'rod'=>17.5,'rue'=>15.0],
            '26'=>['min'=>65,'max'=>67,'cad'=>'78-80','l'=>91,'rod'=>18.5,'rue'=>16.0],
            '28'=>['min'=>71,'max'=>73,'cad'=>'87-89','l'=>100,'rod'=>20.0,'rue'=>18.0],
            '30'=>['min'=>76,'max'=>78,'cad'=>'93-95','l'=>102,'rod'=>21.0,'rue'=>19.0],
            '32'=>['min'=>81,'max'=>83,'cad'=>'98-100','l'=>104,'rod'=>22.0,'rue'=>20.0],
            '34'=>['min'=>86,'max'=>88,'cad'=>'103-105','l'=>106,'rod'=>23.0,'rue'=>21.0],
            '36'=>['min'=>91,'max'=>93,'cad'=>'108-110','l'=>108,'rod'=>24.0,'rue'=>22.0],
            '38'=>['min'=>96,'max'=>98,'cad'=>'113-115','l'=>110,'rod'=>25.0,'rue'=>23.0],
            '40'=>['min'=>101,'max'=>103,'cad'=>'118-120','l'=>112,'rod'=>26.0,'rue'=>24.0]
        ];
        foreach($tab as $t => $d) { 
            if($c >= $d['min'] && $c <= $d['max']) 
                return ['talla'=>$t, 'sug'=>"Cad:{$d['cad']} L:{$d['l']} Rod:{$d['rod']} Rue:{$d['rue']}"];
        }
        return ['talla'=>'Especial', 'sug'=>''];
    }

    public function calcularTallaShort($c) {
        $tab = [
            'T.4'=>['c'=>52,'l'=>36],'T.5'=>['c'=>54,'l'=>38],'T.6'=>['c'=>56,'l'=>40],
            'T.8'=>['c'=>58,'l'=>40],'T.10'=>['c'=>64,'l'=>40]
        ];
        foreach($tab as $t => $d) { if($c <= $d['c']) return ['talla'=>$t, 'sug'=>"L:".$d['l']]; }
        return ['talla'=>'Especial', 'sug'=>''];
    }

    public function calcularTallaFalda($cad) {
        $tab = [
            'T.5'=>['c'=>65,'cad'=>90],'T.6'=>['c'=>66,'cad'=>92],'T.8'=>['c'=>70,'cad'=>96],
            'T.10'=>['c'=>74,'cad'=>100],'T.12'=>['c'=>78,'cad'=>104],'T.14'=>['c'=>82,'cad'=>108],
            'T.15'=>['c'=>84,'cad'=>110],'T.16'=>['c'=>88,'cad'=>114],'T.18'=>['c'=>94,'cad'=>120],
            'T.20'=>['c'=>102,'cad'=>134],'T.22'=>['c'=>108,'cad'=>140]
        ];
        foreach ($tab as $talla => $med) {
            if ($cad <= $med['cad']) return ['talla'=>$talla, 'sug'=>"Cintura ideal: ".$med['c']];
        }
        return ['talla'=>'Especial', 'sug'=>''];
    }
}