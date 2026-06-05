<?php

/**
 * Database Seeder
 * Run: php scripts/seed.php
 */

require_once __DIR__ . '/../bootstrap/app.php';

$db = \App\Core\App::instance()->db();
$hash = password_hash('password', PASSWORD_BCRYPT);
$now = date('Y-m-d H:i:s');

echo "Seeding database...\n";

// Users
$db->query("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?) ON CONFLICT (email) DO NOTHING", [
    'Admin User', 'admin@example.com', $hash, 'admin', $now, $now,
]);
$db->query("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?) ON CONFLICT (email) DO NOTHING", [
    'John Doe', 'user@example.com', $hash, 'user', $now, $now,
]);
$db->query("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?) ON CONFLICT (email) DO NOTHING", [
    'Jane Smith', 'jane@example.com', $hash, 'user', $now, $now,
]);
$db->query("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?) ON CONFLICT (email) DO NOTHING", [
    'Bob Johnson', 'bob@example.com', $hash, 'user', $now, $now,
]);
echo "  Users seeded.\n";

// Categories
$categories = [
    ['Fiction', 'fiction', 'Novels, short stories, and literary fiction from various genres.'],
    ['Science Fiction', 'science-fiction', 'Books about futuristic concepts, space exploration, and advanced technology.'],
    ['Non-Fiction', 'non-fiction', 'Biographies, history, self-help, and educational books.'],
    ['Fantasy', 'fantasy', 'Epic adventures, magical worlds, and mythical creatures.'],
    ['Mystery', 'mystery', 'Detective stories, thrillers, and crime novels.'],
];
foreach ($categories as $cat) {
    $db->query("INSERT INTO categories (name, slug, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?) ON CONFLICT (name) DO NOTHING", [
        $cat[0], $cat[1], $cat[2], $now, $now,
    ]);
}
echo "  Categories seeded.\n";

// Books
$books = [
    ['The Great Gatsby', 'F. Scott Fitzgerald', 'A story of the mysteriously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.', 1925, 1],
    ['To Kill a Mockingbird', 'Harper Lee', 'A novel about racial injustice in the Deep South, seen through the eyes of a young girl.', 1960, 1],
    ['1984', 'George Orwell', 'A dystopian novel set in a totalitarian society ruled by Big Brother.', 1949, 1],
    ['Pride and Prejudice', 'Jane Austen', 'A romantic novel that charts the emotional development of Elizabeth Bennet.', 1813, 1],
    ['Dune', 'Frank Herbert', 'Set on the desert planet Arrakis, Dune is the story of Paul Atreides and his journey.', 1965, 2],
    ['The Martian', 'Andy Weir', 'An astronaut becomes stranded alone on Mars and must find a way to survive.', 2011, 2],
    ['Neuromancer', 'William Gibson', 'A washed-up computer hacker is hired for one last job in cyberspace.', 1984, 2],
    ['Foundation', 'Isaac Asimov', 'A mathematician predicts the fall of the Galactic Empire and creates a plan to save civilization.', 1951, 2],
    ['Sapiens', 'Yuval Noah Harari', 'A brief history of humankind, from the Stone Age to the present day.', 2011, 3],
    ['Educated', 'Tara Westover', 'A memoir about a woman who grows up in a survivalist family and eventually earns a PhD.', 2018, 3],
    ['The Art of War', 'Sun Tzu', 'An ancient Chinese military treatise that has become a classic of strategy and philosophy.', -500, 3],
    ['Thinking, Fast and Slow', 'Daniel Kahneman', 'An exploration of the two systems that drive the way we think—fast intuitive and slow deliberate.', 2011, 3],
    ['The Hobbit', 'J.R.R. Tolkien', 'Bilbo Baggins goes on an adventure to help a group of dwarves reclaim their homeland.', 1937, 4],
    ['A Game of Thrones', 'George R.R. Martin', 'Noble families fight for control of the Iron Throne in the land of Westeros.', 1996, 4],
    ['Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'A young boy discovers he is a wizard and attends a magical school.', 1997, 4],
    ['The Name of the Wind', 'Patrick Rothfuss', 'The story of Kvothe, a legendary figure now living under an assumed name.', 2007, 4],
    ['The Girl with the Dragon Tattoo', 'Stieg Larsson', 'A journalist and a hacker investigate a wealthy family\'s dark secrets.', 2005, 5],
    ['Gone Girl', 'Gillian Flynn', 'A woman disappears on her fifth wedding anniversary — and her husband becomes the prime suspect.', 2012, 5],
    ['The Da Vinci Code', 'Dan Brown', 'A symbologist and a cryptologist unravel a mystery hidden in famous works of art.', 2003, 5],
    ['Sherlock Holmes: A Study in Scarlet', 'Arthur Conan Doyle', 'The first Sherlock Holmes novel, introducing the brilliant detective and his loyal friend Dr. Watson.', 1887, 5],
];

foreach ($books as $i => $book) {
    $db->query("INSERT INTO books (title, author, description, published_year, category_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?) ON CONFLICT DO NOTHING", [
        $book[0], $book[1], $book[2], $book[3], $book[4], $now, $now,
    ]);
}
echo "  Books seeded.\n";

// Reviews
$reviews = [
    [5, 'Absolutely loved this book! The writing is beautiful and the characters feel real. Highly recommended to everyone.'],
    [5, 'A masterpiece. I could not put it down. The story stays with you long after you finish reading.'],
    [4, 'Really enjoyable read. The plot is engaging and well-paced. Would have liked a bit more character development.'],
    [4, 'Great book overall. The author has a unique voice and the story is compelling from start to finish.'],
    [3, 'It was okay. Some parts were really interesting, but others felt a bit slow. Worth reading if you like the genre.'],
    [3, 'Decent read. Not my favorite but I can see why others enjoy it. The middle section dragged a little.'],
    [5, 'One of the best books I have ever read. The themes are timeless and the prose is stunning.'],
    [4, 'Very well written with strong characters. The ending was satisfying. Would recommend.'],
    [2, 'Not really my cup of tea. The pacing was off and I struggled to connect with the main character.'],
    [5, 'Brilliant! Every page was a pleasure to read. I will definitely be reading more from this author.'],
    [4, 'A solid book with interesting ideas. The world-building is excellent and the dialogue feels natural.'],
    [3, 'An average read. Nothing groundbreaking but entertaining enough for a weekend.'],
    [5, 'Absolutely loved this book! The writing is beautiful and the characters feel real.'],
    [4, 'Really enjoyable read. The plot is engaging and well-paced throughout.'],
    [5, 'A masterpiece. I could not put it down. The story stays with you.'],
    [4, 'Great book overall. The author has a unique voice and the story is compelling.'],
    [5, 'One of the best books I have ever read. The themes are timeless.'],
    [3, 'Decent read. Not my favorite but I can see why others enjoy it.'],
    [4, 'Very well written with strong characters. The ending was satisfying.'],
    [5, 'Brilliant! Every page was a pleasure to read. Will read more from this author.'],
];

// Get user IDs (skip admin, use users 2-4) and book IDs 1-20
for ($i = 0; $i < 20; $i++) {
    $userId = ($i % 3) + 2; // Users 2, 3, 4
    $bookId = $i + 1;       // Books 1-20
    $review = $reviews[$i];

    try {
        $db->query("INSERT INTO reviews (user_id, book_id, rating, content, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)", [
            $userId, $bookId, $review[0], $review[1], $now, $now,
        ]);
    } catch (\PDOException $e) {
        // Skip duplicate reviews
        if (!str_contains($e->getMessage(), 'duplicate key')) {
            throw $e;
        }
    }
}
echo "  Reviews seeded.\n";

echo "Seeding complete!\n";
echo "\nTest accounts (password: password):\n";
echo "  Admin: admin@example.com\n";
echo "  User:  user@example.com\n";
echo "  User:  jane@example.com\n";
echo "  User:  bob@example.com\n";
