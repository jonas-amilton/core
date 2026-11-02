<?php

declare(strict_types=1);

namespace Modules\Content\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Content\Data\ClientData;
use Modules\Content\Data\FaqData;
use Modules\Content\Data\TestimonialData;
use Modules\Content\Models\Client;
use Modules\Content\Models\Faq;
use Modules\Content\Models\Testimonial;

interface ContentRepository
{
    /**
     * @return Collection<int, Testimonial>
     */
    public function testimonials(): Collection;

    public function findTestimonial(int $id): ?Testimonial;

    public function createTestimonial(TestimonialData $data): Testimonial;

    public function updateTestimonial(Testimonial $testimonial, TestimonialData $data): Testimonial;

    public function deleteTestimonial(Testimonial $testimonial): void;

    /**
     * @return Collection<int, Faq>
     */
    public function faqs(): Collection;

    public function findFaq(int $id): ?Faq;

    public function createFaq(FaqData $data): Faq;

    public function updateFaq(Faq $faq, FaqData $data): Faq;

    public function deleteFaq(Faq $faq): void;

    /**
     * @return Collection<int, Client>
     */
    public function clients(): Collection;

    public function findClient(int $id): ?Client;

    public function createClient(ClientData $data): Client;

    public function updateClient(Client $client, ClientData $data): Client;

    public function deleteClient(Client $client): void;
}
