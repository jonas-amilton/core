<?php

declare(strict_types=1);

namespace Modules\Content\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Content\Contracts\ContentRepository;
use Modules\Content\Data\ClientData;
use Modules\Content\Data\FaqData;
use Modules\Content\Data\TestimonialData;
use Modules\Content\Models\Client;
use Modules\Content\Models\Faq;
use Modules\Content\Models\Testimonial;

/**
 * Thin facade: the content lists have no business rules beyond ordering, which
 * lives in the repository.
 */
final class ContentService
{
    public function __construct(private ContentRepository $content) {}

    /**
     * @return Collection<int, Testimonial>
     */
    public function testimonials(): Collection
    {
        return $this->content->testimonials();
    }

    public function createTestimonial(TestimonialData $data): Testimonial
    {
        return $this->content->createTestimonial($data);
    }

    public function updateTestimonial(Testimonial $testimonial, TestimonialData $data): Testimonial
    {
        return $this->content->updateTestimonial($testimonial, $data);
    }

    public function deleteTestimonial(Testimonial $testimonial): void
    {
        $this->content->deleteTestimonial($testimonial);
    }

    /**
     * @return Collection<int, Faq>
     */
    public function faqs(): Collection
    {
        return $this->content->faqs();
    }

    public function createFaq(FaqData $data): Faq
    {
        return $this->content->createFaq($data);
    }

    public function updateFaq(Faq $faq, FaqData $data): Faq
    {
        return $this->content->updateFaq($faq, $data);
    }

    public function deleteFaq(Faq $faq): void
    {
        $this->content->deleteFaq($faq);
    }

    /**
     * @return Collection<int, Client>
     */
    public function clients(): Collection
    {
        return $this->content->clients();
    }

    public function createClient(ClientData $data): Client
    {
        return $this->content->createClient($data);
    }

    public function updateClient(Client $client, ClientData $data): Client
    {
        return $this->content->updateClient($client, $data);
    }

    public function deleteClient(Client $client): void
    {
        $this->content->deleteClient($client);
    }
}
