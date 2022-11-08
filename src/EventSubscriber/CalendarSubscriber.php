<?php
namespace App\EventSubscriber;

use App\Repository\TrainingRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private TrainingRepository $trainingRepository;
    private UrlGeneratorInterface $router;

    public function __construct(
        TrainingRepository $trainingRepository,
        UrlGeneratorInterface $router
    ) {
        $this->trainingRepository = $trainingRepository;
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

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $trainings = $this->trainingRepository
            ->createQueryBuilder('training')
            ->where('training.beginAt BETWEEN :start and :end OR training.endAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($trainings as $training) {
            // this create the events with your data (here booking data) to fill calendar
            $trainingEvent = new Event(
                $training->getTitle(),
                $training->getBeginAt(),
                $training->getEndAt() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $trainingEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $trainingEvent->addOption(
                'url',
                $this->router->generate('app_training_show', [
                    'id' => $training->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($trainingEvent);
        }
    }
}