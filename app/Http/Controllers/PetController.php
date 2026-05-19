<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Google\Cloud\Firestore\FirestoreClient;

class PetController extends Controller
{
    private function getFirestoreInstance()
    {
        $path = storage_path('firebase_credentials.json');
        if (!file_exists($path)) {
            $path = storage_path('app/firebase_credentials.json');
        }
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $path);
        return new FirestoreClient(['transport' => 'rest']);
    }

    public function calculate(Request $request): RedirectResponse
    {
        // Force authentication security check
        if (!session()->has('user_email')) return redirect('/login');

        // Capture all onboarding parameters from Cassy's wizard UI layout
        $petName = $request->input('pet_name');
        $species = strtolower($request->input('species')); // 'dog' or 'cat'
        $gender = $request->input('gender');               // 'Boy' or 'Girl'
        $breed = $request->input('breed');
        $ageValue = floatval($request->input('age_value'));
        $ageUnit = $request->input('age_unit');             // 'Years' or 'Months'
        $weight = floatval($request->input('weight'));
        $weightUnit = $request->input('weight_unit');       // 'lbs' or 'kg'
        $bodyCondition = $request->input('body_condition'); // 'Underweight', 'Ideal Weight', 'Overweight'
        $activityLevel = $request->input('activity_level'); // 'Less Active', 'Semi Active', 'Active', 'Highly Active'
        $feedingFrequency = $request->input('feeding_frequency');

        // Check if pet is a puppy (Canine under 1 year old)
        $isPuppy = false;
        if ($species === 'dog') {
            if ($ageUnit === 'Months' && $ageValue < 12) {
                $isPuppy = true;
            } elseif ($ageUnit === 'Years' && $ageValue < 1) {
                $isPuppy = true;
            }
        }

        // Normalize weight to lbs for standard cookbook equations
        $weightInLbs = $weight;
        if ($weightUnit === 'kg') {
            $weightInLbs = $weight * 2.20462;
        }

        // 1. Metabolic Energy Target Calculations
        if ($species === 'cat') {
            $baseCalories = ($weightInLbs * 22) + 70;
        } else {
            // Puppies require higher caloric density targets than adult dogs
            $baseCalories = $isPuppy ? ($weightInLbs * 45) + 70 : ($weightInLbs * 30) + 70;
        }

        // 2. Apply Onboarding Activity Multipliers
        $multiplier = 1.0;
        switch ($activityLevel) {
            case 'Less Active':    $multiplier = 0.8; break;
            case 'Semi Active':    $multiplier = 1.0; break;
            case 'Active':         $multiplier = 1.2; break;
            case 'Highly Active':  $multiplier = 1.4; break;
        }

        // 3. Apply Health Body Condition Target Offsets
        if ($bodyCondition === 'Underweight') {
            $multiplier += 0.15; // Increase target to reach ideal body weight
        } elseif ($bodyCondition === 'Overweight') {
            $multiplier -= 0.15; // Lower target safely
        }

        $calories = round($baseCalories * $multiplier);

        // 4. Determine Split Portions Based on Daily Feeding Frequency
        $totalMeals = 2;
        if (str_contains($feedingFrequency, '1')) $totalMeals = 1;
        if (str_contains($feedingFrequency, '3')) $totalMeals = 3;
        if (str_contains($feedingFrequency, '4')) $totalMeals = 4;

        // 5. Dynamic 5-Recipe Cookbook Engine Match Matrix
        if ($species === 'cat') {
            // Alternate between Feline Recipe 1 and Recipe 2 randomly or sequentially
            $choice = (rand(1, 2) === 1);
            if ($choice) {
                $recipeTitle = "Violet's Chicken Dinner";
                $premixGrams = round(($calories / 100) * 1.4, 1);
                $ingredients = "• 900g Raw Chicken Thighs (skin and bone removed)\n• 100g Chicken Liver\n• 2 Large Hard-Boiled Eggs\n• 1.5 tbsp Holistic Vet Blend Feline Regular Premix";

                $proteinGrams = round((900 / $totalMeals) * ($weightInLbs / 10));
                $carbsGrams = 0; // Low-carb ketogenic balance for cats
            } else {
                $recipeTitle = "Chicken and Sardine Special";
                $premixGrams = round(($calories / 100) * 1.6, 1);
                $ingredients = "• 800g Cooked Chicken Breast\n• 1 Can Sardines in Water (drained completely, no added salt)\n• 50g Steamed Chopped Spinach\n• 2 tbsp Holistic Vet Blend Feline Regular Premix";

                $proteinGrams = round((850 / $totalMeals) * ($weightInLbs / 10));
                $carbsGrams = round((50 / $totalMeals) * ($weightInLbs / 10));
            }
        } else {
            if ($isPuppy) {
                // Recipe 5: Puppy Food Cookbook standard configuration
                $recipeTitle = "Puppy Growth & Healthy Start Recipe";
                $premixGrams = round(($calories / 100) * 2.3, 1);
                $ingredients = "• 1.2kg Ground Whole Beef (85% lean)\n• 250g Cooked Sweet Potato Puree\n• 100g Steamed Chopped Zucchini\n• 4 tbsp Holistic Vet Blend Puppy Foundation Regular Premix";

                $proteinGrams = round((1200 / $totalMeals) * ($weightInLbs / 30));
                $carbsGrams = round((350 / $totalMeals) * ($weightInLbs / 30));
            } else {
                $choice = (rand(1, 2) === 1);
                if ($choice) {
                    // Recipe 3: Big's Turkey and Macaroni Florentine
                    $recipeTitle = "Big's Turkey and Macaroni Florentine";
                    $premixGrams = round(($calories / 100) * 1.8, 1);
                    $ingredients = "• 1kg Lean Ground Turkey (cooked and drained)\n• 300g Cooked Macaroni Pasta\n• 150g Chopped Steamed Broccoli florets\n• 3 tbsp Holistic Vet Blend Canine Regular Premix";

                    $proteinGrams = round((1000 / $totalMeals) * ($weightInLbs / 40));
                    $carbsGrams = round((450 / $totalMeals) * ($weightInLbs / 40));
                } else {
                    // Recipe 4: Turkey, Brown Rice, Carrots and Kale
                    $recipeTitle = "Turkey, Brown Rice, Carrots and Kale";
                    $premixGrams = round(($calories / 100) * 2.1, 1);
                    $ingredients = "• 1.2kg Cooked Ground Turkey\n• 400g Steamed Brown Rice\n• 100g Pureed Baby Carrots\n• 100g Finely Chopped Fresh Kale\n• 3.5 tbsp Holistic Vet Blend Canine Limited Premix";

                    $proteinGrams = round((1200 / $totalMeals) * ($weightInLbs / 45));
                    $carbsGrams = round((600 / $totalMeals) * ($weightInLbs / 45));
                }
            }
        }

        // Calculate portions per single feeding cycle instance
        $premixPerMeal = round($premixGrams / $totalMeals, 1);
        if ($proteinGrams < 15) $proteinGrams = 45; // Safety floor parameters
        if ($carbsGrams < 10 && $species === 'dog') $carbsGrams = 25;

        // 6. Push and record data seamlessly to Firestore
        $firestore = $this->getFirestoreInstance();
        $firestore->collection('pet_calculations')->add([
            'owner_email' => session('user_email'),
            'pet_name' => $petName,
            'species' => ucfirst($species),
            'gender' => $gender,
            'breed' => $breed,
            'age_value' => $ageValue,
            'age_unit' => $ageUnit,
            'weight' => $weight,
            'weight_unit' => $weightUnit,
            'body_condition' => $bodyCondition,
            'activity_level' => $activityLevel,
            'feeding_frequency' => $feedingFrequency,
            'recipe_title' => $recipeTitle,
            'calories' => $calories,
            'premix_grams' => $premixPerMeal,
            'protein_grams' => $proteinGrams,
            'carbs_grams' => $carbsGrams,
            'ingredients' => $ingredients,
            'timestamp' => now()->toIso8601String()
        ]);

        // Redirect directly to the plan tab to stop the white-out bug
        return redirect('/dashboard?tab=plan')->with('success', 'Diet Profile Formulation Logged Successfully!');
    }
}
