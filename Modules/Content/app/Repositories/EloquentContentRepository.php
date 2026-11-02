<?php

declare(strict_types=1);

namespace Modules\Content\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Content\Contracts\ContentRepository;
use Modules\Content\Data\ClientData;
use Modules\Content\Data\FaqData;
use Modules\Content\Data\TestimonialData;
use Modules\Content\Models\Client;
use Modules\Content\Models\Faq;
use Modules\Content\Models\Testimonial;

final class EloquentContentRepository implements ContentRepository
{
    /**
     * @return Collection<int, Testimonial>
     */
    public function testimonials(): Collection
    {
        return Testimonial::query()->orderBy('sort_order')->orderBy('id')->get();
    }

    public function findTestimonial(int $id): ?Testimonial
    {
        return Testimonial::query()->find($id);
    }

    public function createTestimonial(TestimonialData $data): Testimonial
    {
        return Testimonial::query()->create($this->testimonialAttributes($data));
    }

    public function updateTestimonial(Testimonial $testimonial, TestimonialData $data): Testimonial
    {
        $testimonial->update($this->testimonialAttributes($data));

        return $testimonial->refresh();
    }

    public function deleteTestimonial(Testimonial $testimonial): void
    {
        $testimonial->delete();
    }

    /**
     * @return Collection<int, Faq>
     */
    public function faqs(): Collection
    {
        return Faq::query()->orderBy('sort_order')->orderBy('id')->get();
    }

    public function findFaq(int $id): ?Faq
    {
        return Faq::query()->find($id);
    }

    public function createFaq(FaqData $data): Faq
    {
        return Faq::query()->create($this->faqAttributes($data));
    }

    public function updateFaq(Faq $faq, FaqData $data): Faq
    {
        $faq->update($this->faqAttributes($data));

        return $faq->refresh();
    }

    public function deleteFaq(Faq $faq): void
    {
        $faq->delete();
    }

    /**
     * @return Collection<int, Client>
     */
    public function clients(): Collection
    {
        return Client::query()->orderBy('sort_order')->orderBy('id')->get();
    }

    public function findClient(int $id): ?Client
    {
        return Client::query()->find($id);
    }

    public function createClient(ClientData $data): Client
    {
        return Client::query()->create($this->clientAttributes($data));
    }

    public function updateClient(Client $client, ClientData $data): Client
    {
        $client->update($this->clientAttributes($data));

        return $client->refresh();
    }

    public function deleteClient(Client $client): void
    {
        $client->delete();
    }

    /**
     * @return array<string, mixed>
     */
    private function testimonialAttributes(TestimonialData $data): array
    {
        return [
            'author' => $data->author,
            'role' => $data->role,
            'quote' => $data->quote,
            'avatar' => $data->avatar,
            'sort_order' => $data->sortOrder,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function faqAttributes(FaqData $data): array
    {
        return [
            'question' => $data->question,
            'answer' => $data->answer,
            'sort_order' => $data->sortOrder,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function clientAttributes(ClientData $data): array
    {
        return [
            'name' => $data->name,
            'logo' => $data->logo,
            'url' => $data->url,
            'sort_order' => $data->sortOrder,
        ];
    }
}
