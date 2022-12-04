<?php
namespace App\EventSubscriber;

use App\Repository\EventRepository;
use App\Repository\TrainingRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private TrainingRepository $trainingRepository;
    private EventRepository $eventRepository;
    private UrlGeneratorInterface $router;

    public function __construct(
        TrainingRepository $trainingRepository,
        EventRepository $eventRepository,
        UrlGeneratorInterface $router
    ) {
        $this->trainingRepository = $trainingRepository;
        $this->eventRepository = $eventRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $trainings = $this->trainingRepository
            ->createQueryBuilder('training')
            ->where('training.beginAt BETWEEN :start and :end OR training.endAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($trainings as $training) {
            $trainingEvent = new Event(
                $training->getTitle(),
                $training->getBeginAt(),
                $training->getEndAt()
            );

            $trainingEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
                'editable'=> false,
            ]);

            $calendar->addEvent($trainingEvent);
        }


        $event = $this->eventRepository->findAll();
        foreach ($event as $eventCompetition) {
            $event = new Event(
                $eventCompetition->getTitle(),
                $eventCompetition->getDate(),
                $eventCompetition->getDate(),
                $eventCompetition->getId()
            );

            $event->setOptions([
                'backgroundColor' => 'blue',
                'borderColor' => 'blue',
                'editable'=> false,
                'className' => 'eventCalendar',
            ]);

            $calendar->addEvent($event);
        }

    }
}