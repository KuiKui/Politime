<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Politime\Topic\Topic;
use Politime\Topic\TopicService;
use Politime\TimeSlot\TimeSlot;
use Politime\TimeSlot\TimeSlotService;

/*******************************************************************************
 * Micro-commande permettant de saisir les sujets abordés chaque jour de travail
 * -----------------------------------------------------------------------------
 *
 * TOPIC : Sujet qu'il est possible d'avoir abordé au cours de sa journée de
 * travail.
 *
 * Ex :
 * - Multilingue
 * - Nouveaux Statuts de commande
 * - Intégration Client 243
 * - Infra
 * - Code Review
 * - etc.
 *
 * La liste des sujets disponibles se trouve dans un fichier JSON qui peut être
 * centralisé sur un serveur accessible à tous les utilisateurs et qui doit
 * être maintenu par le responsable de l'équipe.
 *
 * Afin de simplifier la saisie il est aussi possible que chaque utilisateur
 * utilise une réduction du fichier central (mais il doit s'inquiéter de le
 * maintenir à jour).
 *
 * Afin de configurer le chemin vers ce fichier, créer une variable
 * d'environnement 'POLITIME_TOPICS_FILENAME' contenant le chemin absolu vers
 * le fichier JSON souhaité.
 *
 * -----------------------------------------------------------------------------
 *
 * TIME SLOT :
 *
 * Espace de temps (actuellement une journée) référençant un ou plusieurs
 * topics ayant été abordés.
 *
 * Les timeslots sont sauvegardés dans un fichier JSON dont il est possible de
 * configurer le nom en utilisant la variable d'environnement
 * 'POLITIME_TIMESLOTS_FILENAME'.
 *
 * Il est donc possible de sauvegarder son fichier sur un serveur central en
 * spécifiant son nom (ex: //srv-cra/politime/michael-jordan.json).
 */

define('DEFAULT_TOPICS_FILENAME', __DIR__ . '/../data/topics-example.json');
define('DEFAULT_TIMESLOTS_FILENAME', __DIR__.'/../data/timeslots.json');

$app = new Silly\Application();

$app->command('politime [subcommand] [param]', function ($subcommand, $param = '', InputInterface $input, OutputInterface $output) {
    $io = new SymfonyStyle($input, $output);

    // Chargement des services
    $topicService = new TopicService(getenv('POLITIME_TOPICS_FILENAME') ?: DEFAULT_TOPICS_FILENAME);
    $timeSlotService = new TimeSlotService(getenv('POLITIME_TIMESLOTS_FILENAME') ?: DEFAULT_TIMESLOTS_FILENAME);

    // Définition des commandes
    switch ($subcommand) {
        case 'list':
            switch ($param) {
                case 'topics':
                    $io->table(['Id', 'Name'], array_map(function (Topic $topic) {
                        return [$topic->id, $topic->name];
                    }, $topicService->getList()));

                    break;

                default:
                    $io->table(['Date', 'Topics'], $timeSlotService->getList());

                    break;
            }
            break;

        case 'set':
        case 'add':
            // Traitement de la date en paramètre
            try {
                $date = new DateTimeImmutable($param);
                $date = $date->format('Y-m-d');
            } catch (\Exception $e) {
                $io->warning('Unprocessable date : '.$param);

                break;
            }

            // Sélection des sujets par l'utilisateur
            $selectedTopicNames = selectTopics($date, $topicService->getList(), $io);
            $selectedTopics = $topicService->searchByName($selectedTopicNames);

            // Affichage du récapitulatif du choix de l'utilisateur
            $io->table(['Topics selected for '.$date], array_map(function ($selectedTopic) {
                return [$selectedTopic->name];
            }, $selectedTopics));

            // Demande de confirmation avant d'enregistrer
            if (!$io->confirm('Are you sure?')) {
                break;
            }

            $overwrite = ($subcommand == 'set');

            // Enregistrement du timeSlot
            if (!$timeSlotService->save($date, $selectedTopics, $overwrite)) {
                $io->warning('Time slot not saved.');
            }
            $io->success('Time slot saved.');

            break;

        default:
            $output->writeln('Unknown command');

            break;
    }
});

$app->run();

function selectTopics($date, array $topics, SymfonyStyle $io)
{
    // Récupération du nom de tous les topics
    $choices = array_map(function (Topic $topic) {
        return $topic->name;
    }, $topics);

    // Affichage de tous les sujets et attente de la réponse de l'utilisateur
    $question = new ChoiceQuestion(sprintf("Select topic(s) for %s (use ',' for multiple choices)", $date), $choices);
    $question->setMultiselect(true);

    return (array) $io->askQuestion($question);
}
