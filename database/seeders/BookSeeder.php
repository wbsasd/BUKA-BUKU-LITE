<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->pluck('id')->all();

        // Jika categories kosong, tidak bisa mengisi category_id.
        // Pada kondisi ini, seeder categories seharusnya sudah dipanggil oleh DatabaseSeeder.
        if (empty($categories)) {
            return;
        }

        $books = [
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'category_index' => 0,
                'cover' => 'covers/clean-code.jpg',
                'pdf' => 'pdfs/clean-code.pdf',
                'description' => 'A handbook of agile software craftsmanship.',
                'stock' => 5,
                'publication_year' => 2008,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'publisher' => 'Avery',
                'category_index' => 1,
                'cover' => 'covers/atomic-habits.jpg',
                'pdf' => 'pdfs/atomic-habits.pdf',
                'description' => 'An easy & proven way to build good habits & break bad ones.',
                'stock' => 7,
                'publication_year' => 2018,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt & David Thomas',
                'publisher' => 'Addison-Wesley',
                'category_index' => 4,
                'cover' => 'covers/pragmatic-programmer.jpg',
                'pdf' => 'pdfs/pragmatic-programmer.pdf',
                'description' => 'Your journey to mastery through practical software advice.',
                'stock' => 6,
                'publication_year' => 1999,
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'publisher' => 'Addison-Wesley',
                'category_index' => 3,
                'cover' => 'covers/design-patterns.jpg',
                'pdf' => 'pdfs/design-patterns.pdf',
                'description' => 'Elements of reusable object-oriented software.',
                'stock' => 4,
                'publication_year' => 1994,
            ],
            [
                'title' => 'Introduction to Algorithms',
                'author' => 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein',
                'publisher' => 'MIT Press',
                'category_index' => 2,
                'cover' => 'covers/intro-algorithms.jpg',
                'pdf' => 'pdfs/intro-algorithms.pdf',
                'description' => 'A comprehensive set of algorithms and data structures.',
                'stock' => 3,
                'publication_year' => 2009,
            ],
            [
                'title' => 'Clean Architecture',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'category_index' => 4,
                'cover' => 'covers/clean-architecture.jpg',
                'pdf' => 'pdfs/clean-architecture.pdf',
                'description' => 'A craftsman\'s guide to software structure and design.',
                'stock' => 5,
                'publication_year' => 2017,
            ],
            [
                'title' => 'You Don\'t Know JS',
                'author' => 'Kyle Simpson',
                'publisher' => 'O\'Reilly Media',
                'category_index' => 0,
                'cover' => 'covers/you-dont-know-js.jpg',
                'pdf' => 'pdfs/you-dont-know-js.pdf',
                'description' => 'Deep dives into the mechanics of JavaScript.',
                'stock' => 8,
                'publication_year' => 2015,
            ],
            [
                'title' => 'Deep Learning',
                'author' => 'Ian Goodfellow, Yoshua Bengio, Aaron Courville',
                'publisher' => 'MIT Press',
                'category_index' => 1,
                'cover' => 'covers/deep-learning.jpg',
                'pdf' => 'pdfs/deep-learning.pdf',
                'description' => 'An introduction to deep learning with practical examples.',
                'stock' => 2,
                'publication_year' => 2016,
            ],
            [
                'title' => 'The Science of Reading',
                'author' => 'Daniel T. Willingham',
                'publisher' => 'Jossey-Bass',
                'category_index' => 5,
                'cover' => 'covers/science-of-reading.jpg',
                'pdf' => 'pdfs/science-of-reading.pdf',
                'description' => 'How research informs teaching reading.',
                'stock' => 6,
                'publication_year' => 2017,
            ],
            [
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'publisher' => 'Harper',
                'category_index' => 5,
                'cover' => 'covers/sapiens.jpg',
                'pdf' => 'pdfs/sapiens.pdf',
                'description' => 'A brief history of humankind.',
                'stock' => 4,
                'publication_year' => 2011,
            ],
        ];

        foreach ($books as $book) {
            $categoryId = $categories[$book['category_index'] % count($categories)];

            // updateOrCreate: mencegah duplikat saat seeder dijalankan berulang.
            Book::updateOrCreate(
                ['title' => $book['title']],
                [
                    'author' => $book['author'],
                    'publisher' => $book['publisher'],
                    'category_id' => $categoryId,
                    'cover_image' => $book['cover'], // dummy path di storage/public
                    'file_pdf' => $book['pdf'],       // dummy path di storage/public
                    'description' => $book['description'],
                    'stock' => $book['stock'],
                    'publication_year' => $book['publication_year'],
                ]
            );
        }
    }
}

