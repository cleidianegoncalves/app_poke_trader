<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trade;
use Symfony\Component\HttpFoundation\JsonResponse;

class TradeController extends AbstractController
{

    /**
     * @Route("/trade", name="trade")
     */
    public function index(): Response
    {
        $pokemons = $this->getPokemons();

        return $this->render('trade/index.html.php', [
                'pokemons' => $pokemons
        ]);
    }

    public function history(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Trade::class);
        $data = $request->query->all();

        $page = (isset($data["PAGE"]) ? $data["PAGE"] : 1);
        $trades = $repository->findAllQueryBuilder($page);

        $total = count($repository->findAll());
        $totalPage = ceil($total / Trade::COUNT_PER_PAGE);
        $pages = array();
        for ($i = 1; $i <= $totalPage; $i++) {
            $pages[] = $i;
        }

        return $this->render('trade/history.html.php', [
                'trades' => $trades, 'pages' => $pages, 'pageSelected' => $page
        ]);
    }

    public function show($id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Trade::class);
        $trade = $repository->find($id);

        return $this->render('trade/show.html.php', [
                'trade' => $trade,
        ]);
    }

    public function verify(Request $request): Response
    {
        $save = json_decode($request->get("save"));
        if ($save == true) {
            $data = $request->get("pokemon");

            $aBase = $this->getBaseTotal($data["left"], $data["right"]);

            $trade = new Trade();
            $trade->setBaseExperienceLeft($aBase["left"]);
            $trade->setBaseExperienceRight($aBase["right"]);
            $trade->setPokemonsLeft(($data["left"]));
            $trade->setPokemonsRight(($data["right"]));
            $trade->setIsFair($this->verifyIsFair($aBase["left"], $aBase["right"]));
            $trade->setDateInsert(new \Datetime('now', new \DateTimezone('America/Sao_Paulo')));

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($trade);
            $doctrine->flush();

            $return["msg"] = "OK";
        } else {
            $data = $request->get("pokemon");
            $return["msg"] = "A troca é justa.";
            $aBase = $this->getBaseTotal($data["left"], $data["right"]);

            if (!$this->verifyIsFair($aBase["left"], $aBase["right"])) {
                $return["msg"] = "A troca não é justa.";
            }
        }
        return $this->json($return);
    }

    public function verifyIsFair($total_left, $total_right)
    {
        $accuracy = Trade::ACCURACY;
        $diferencaXP = $total_left - $total_right;
        if ($diferencaXP <= $accuracy && $diferencaXP >= -$accuracy) {
            return true;
        }
        return false;
    }

    public function getBaseTotal($pokemon_left, $pokemon_right)
    {
        $aBaseExperience = array();
        foreach ($pokemon_left as $pokemon) {
            $aBaseExperience["left"][] = $pokemon["xp"];
        }
        foreach ($pokemon_right as $pokemon) {
            $aBaseExperience["right"][] = $pokemon["xp"];
        }
        return array("left" => array_sum($aBaseExperience["left"]), "right" => array_sum($aBaseExperience["right"]));
    }

    public function addPokemon(Request $request): Response
    {
        $pokemon = $this->getPokemon($request->get("name"));
        return $this->json($pokemon);
    }

    private function getPokemons()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://pokeapi.co/api/v2/pokemon?limit=151');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $varsResult = json_decode($result, true);
        $pokemons = array();

        foreach ($varsResult['results'] as $key => $pokemon) {
            $pokemons[] = $pokemon['name'];
        }

        return $pokemons;
    }

    private function getPokemon($nomePokemon)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://pokeapi.co/api/v2/pokemon/' . $nomePokemon);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $varsResult = json_decode($result, true);

        if (!$varsResult) {
            return null;
        }

        $pokemons = array(
            "id" => $varsResult["id"],
            "name" => $varsResult["name"],
            "xp" => $varsResult['base_experience'],
            "img" => $varsResult['sprites']['front_default'],
        );

        return $pokemons;
    }
}
