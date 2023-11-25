<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('firstname'),
                TextInput::make('lastname'),
                TextInput::make('phone_number'),
                TextInput::make('email'),
                TextInput::make('address'),
                TextInput::make('zip_code'),
                DatePicker::make('birth_date'),
                DatePicker::make('date_hired'),

                Select::make('department_id')
                    ->relationship('department','name'),

                Select::make('country_id')
                    ->relationship('country','name')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('state_id',null)),

                Select::make('state_id')
                    ->options(function(callable $get) {
                        $country = Country::find($get('country_id'));
                        if(!$country)
                        {
                            return State::all()->pluck('name','id');
                        }
                        return $country->states->pluck('name','id');

                })->live(),
                
                Select::make('city_id')
                    ->relationship('city','name')
                    ->options(function (callable $get) {
                        $state = State::find($get('state_id'));
                        if(! $state)
                        {
                            return City::all()->pluck('name','id');
                        }
                        return $state->cities->pluck('name','id');
                    }) ,


                FileUpload::make('image')
                    ->preserveFilenames(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('firstname'),
                TextColumn::make('lastname'),
                TextColumn::make('phone_number'),
                TextColumn::make('email'),
                TextColumn::make('address'),
                TextColumn::make('department.name'),
                TextColumn::make('city.name'),
                TextColumn::make('state.name'),
                TextColumn::make('country.name'),
                TextColumn::make('application.experience')
                          ->label('Experience'),
                TextColumn::make('zip_code'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            EmployeeResource\Widgets\EmployeerOverview::class,
        ];
    }

    
}
