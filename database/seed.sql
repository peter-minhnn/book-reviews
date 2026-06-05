-- Book Review Seed Data
-- Run after schema.sql
-- All passwords: "password" (bcrypt hash)

BEGIN;

-- Seed users (password = "password")
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin User', 'admin@example.com', '$2y$10$4RJrbDPOrMRoUVU5cDzNDuyYXvlL.nLi6NcKDw87eC8oJrl/3ts5.', 'admin', NOW(), NOW()),
('John Doe', 'user@example.com', '$2y$10$4RJrbDPOrMRoUVU5cDzNDuyYXvlL.nLi6NcKDw87eC8oJrl/3ts5.', 'user', NOW(), NOW()),
('Jane Smith', 'jane@example.com', '$2y$10$4RJrbDPOrMRoUVU5cDzNDuyYXvlL.nLi6NcKDw87eC8oJrl/3ts5.', 'user', NOW(), NOW()),
('Bob Johnson', 'bob@example.com', '$2y$10$4RJrbDPOrMRoUVU5cDzNDuyYXvlL.nLi6NcKDw87eC8oJrl/3ts5.', 'user', NOW(), NOW());

-- Seed categories
INSERT INTO categories (name, slug, description, created_at, updated_at) VALUES
('Fiction', 'fiction', 'Novels, short stories, and literary fiction from various genres.', NOW(), NOW()),
('Science Fiction', 'science-fiction', 'Books about futuristic concepts, space exploration, and advanced technology.', NOW(), NOW()),
('Non-Fiction', 'non-fiction', 'Biographies, history, self-help, and educational books.', NOW(), NOW()),
('Fantasy', 'fantasy', 'Epic adventures, magical worlds, and mythical creatures.', NOW(), NOW()),
('Mystery', 'mystery', 'Detective stories, thrillers, and crime novels.', NOW(), NOW());

-- Seed 20 books (4 per category)
INSERT INTO books (title, author, description, published_year, category_id, created_at, updated_at) VALUES
-- Fiction (id=1)
('The Great Gatsby', 'F. Scott Fitzgerald', 'A story of the mysteriously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.', 1925, 1, NOW(), NOW()),
('To Kill a Mockingbird', 'Harper Lee', 'A novel about racial injustice in the Deep South, seen through the eyes of a young girl.', 1960, 1, NOW(), NOW()),
('1984', 'George Orwell', 'A dystopian novel set in a totalitarian society ruled by Big Brother.', 1949, 1, NOW(), NOW()),
('Pride and Prejudice', 'Jane Austen', 'A romantic novel that charts the emotional development of Elizabeth Bennet.', 1813, 1, NOW(), NOW()),
-- Science Fiction (id=2)
('Dune', 'Frank Herbert', 'Set on the desert planet Arrakis, Dune is the story of Paul Atreides and his journey.', 1965, 2, NOW(), NOW()),
('The Martian', 'Andy Weir', 'An astronaut becomes stranded alone on Mars and must find a way to survive.', 2011, 2, NOW(), NOW()),
('Neuromancer', 'William Gibson', 'A washed-up computer hacker is hired for one last job in cyberspace.', 1984, 2, NOW(), NOW()),
('Foundation', 'Isaac Asimov', 'A mathematician predicts the fall of the Galactic Empire and creates a plan to save civilization.', 1951, 2, NOW(), NOW()),
-- Non-Fiction (id=3)
('Sapiens', 'Yuval Noah Harari', 'A brief history of humankind, from the Stone Age to the present day.', 2011, 3, NOW(), NOW()),
('Educated', 'Tara Westover', 'A memoir about a woman who grows up in a survivalist family and eventually earns a PhD.', 2018, 3, NOW(), NOW()),
('The Art of War', 'Sun Tzu', 'An ancient Chinese military treatise that has become a classic of strategy and philosophy.', -500, 3, NOW(), NOW()),
('Thinking, Fast and Slow', 'Daniel Kahneman', 'An exploration of the two systems that drive the way we think—fast intuitive and slow deliberate.', 2011, 3, NOW(), NOW()),
-- Fantasy (id=4)
('The Hobbit', 'J.R.R. Tolkien', 'Bilbo Baggins goes on an adventure to help a group of dwarves reclaim their homeland.', 1937, 4, NOW(), NOW()),
('A Game of Thrones', 'George R.R. Martin', 'Noble families fight for control of the Iron Throne in the land of Westeros.', 1996, 4, NOW(), NOW()),
('Harry Potter and the Sorcerer''s Stone', 'J.K. Rowling', 'A young boy discovers he is a wizard and attends a magical school.', 1997, 4, NOW(), NOW()),
('The Name of the Wind', 'Patrick Rothfuss', 'The story of Kvothe, a legendary figure now living under an assumed name.', 2007, 4, NOW(), NOW()),
-- Mystery (id=5)
('The Girl with the Dragon Tattoo', 'Stieg Larsson', 'A journalist and a hacker investigate a wealthy family''s dark secrets.', 2005, 5, NOW(), NOW()),
('Gone Girl', 'Gillian Flynn', 'A woman disappears on her fifth wedding anniversary — and her husband becomes the prime suspect.', 2012, 5, NOW(), NOW()),
('The Da Vinci Code', 'Dan Brown', 'A symbologist and a cryptologist unravel a mystery hidden in famous works of art.', 2003, 5, NOW(), NOW()),
('Sherlock Holmes: A Study in Scarlet', 'Arthur Conan Doyle', 'The first Sherlock Holmes novel, introducing the brilliant detective and his loyal friend Dr. Watson.', 1887, 5, NOW(), NOW());

-- Seed 20 reviews (spread across users 2-4 and books 1-20)
INSERT INTO reviews (user_id, book_id, rating, content, created_at, updated_at) VALUES
(2, 1, 5, 'Absolutely loved this book! The writing is beautiful and the characters feel real. Highly recommended to everyone.', NOW(), NOW()),
(3, 2, 5, 'A masterpiece. I could not put it down. The story stays with you long after you finish reading.', NOW(), NOW()),
(4, 3, 4, 'Really enjoyable read. The plot is engaging and well-paced. Would have liked a bit more character development.', NOW(), NOW()),
(2, 4, 4, 'Great book overall. The author has a unique voice and the story is compelling from start to finish.', NOW(), NOW()),
(3, 5, 3, 'It was okay. Some parts were really interesting, but others felt a bit slow. Worth reading if you like the genre.', NOW(), NOW()),
(4, 6, 3, 'Decent read. Not my favorite but I can see why others enjoy it. The middle section dragged a little.', NOW(), NOW()),
(2, 7, 5, 'One of the best books I have ever read. The themes are timeless and the prose is stunning.', NOW(), NOW()),
(3, 8, 4, 'Very well written with strong characters. The ending was satisfying. Would recommend.', NOW(), NOW()),
(4, 9, 2, 'Not really my cup of tea. The pacing was off and I struggled to connect with the main character.', NOW(), NOW()),
(2, 10, 5, 'Brilliant! Every page was a pleasure to read. I will definitely be reading more from this author.', NOW(), NOW()),
(3, 11, 4, 'A solid book with interesting ideas. The world-building is excellent and the dialogue feels natural.', NOW(), NOW()),
(4, 12, 3, 'An average read. Nothing groundbreaking but entertaining enough for a weekend.', NOW(), NOW()),
(2, 13, 5, 'Absolutely loved this book! The writing is beautiful and the characters feel real.', NOW(), NOW()),
(3, 14, 4, 'Really enjoyable read. The plot is engaging and well-paced throughout.', NOW(), NOW()),
(4, 15, 5, 'A masterpiece. I could not put it down. The story stays with you.', NOW(), NOW()),
(2, 16, 4, 'Great book overall. The author has a unique voice and the story is compelling.', NOW(), NOW()),
(3, 17, 5, 'One of the best books I have ever read. The themes are timeless.', NOW(), NOW()),
(4, 18, 3, 'Decent read. Not my favorite but I can see why others enjoy it.', NOW(), NOW()),
(2, 19, 4, 'Very well written with strong characters. The ending was satisfying.', NOW(), NOW()),
(3, 20, 5, 'Brilliant! Every page was a pleasure to read. Will read more from this author.', NOW(), NOW());

COMMIT;
